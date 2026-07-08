<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Vanguard\DocumentAcknowledgement;
use Vanguard\DocumentAcknowledgementAssignment;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Http\Requests\DocumentAcknowledgement\AcknowledgeDocumentAcknowledgementRequest;
use Vanguard\Http\Requests\DocumentAcknowledgement\StoreDocumentAcknowledgementRequest;
use Vanguard\Http\Requests\DocumentAcknowledgement\UpdateDocumentAcknowledgementRequest;
use Vanguard\Notifications\DocumentAcknowledgementAssigned;
use Vanguard\Notifications\DocumentAcknowledgementSigned;
use Vanguard\Services\DocumentAcknowledgementPdfService;

class DocumentAcknowledgementController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', DocumentAcknowledgement::class);

        $query = DocumentAcknowledgement::query();

        if ($search = $request->get('search')) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        $documents = $query->latest()->paginate(20);

        return view('document_acknowledgements.index', compact('documents'));
    }

    public function create()
    {
        $this->authorize('create', DocumentAcknowledgement::class);

        return view('document_acknowledgements.create');
    }

    public function store(StoreDocumentAcknowledgementRequest $request)
    {
        $document = new DocumentAcknowledgement($request->validated());
        $document->created_by = $request->user()->id;

        if ($request->hasFile('original_file')) {
            $path = $request->file('original_file')->store('document_acknowledgements', 'public');
            $document->original_file_path = $path;
            $document->original_file_name = $request->file('original_file')->getClientOriginalName();
        }

        $document->save();
        $document->logs()->create([
            'user_id' => $request->user()->id,
            'action' => 'Document created',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('compliance.document_acknowledgements.index')
            ->with('success', __('Document acknowledgement created successfully.'));
    }

    public function show(DocumentAcknowledgement $document)
    {
        $this->authorize('view', $document);

        $assignments = $document->assignments()->with('user')->get();

        return view('document_acknowledgements.show', compact('document', 'assignments'));
    }

    public function edit(DocumentAcknowledgement $document)
    {
        $this->authorize('update', $document);

        return view('document_acknowledgements.edit', compact('document'));
    }

    public function update(UpdateDocumentAcknowledgementRequest $request, DocumentAcknowledgement $document)
    {
        $this->authorize('update', $document);

        $document->fill($request->validated());

        if ($request->hasFile('original_file')) {
            if ($document->original_file_path) {
                Storage::disk('public')->delete($document->original_file_path);
            }

            $path = $request->file('original_file')->store('document_acknowledgements', 'public');
            $document->original_file_path = $path;
            $document->original_file_name = $request->file('original_file')->getClientOriginalName();
        }

        $document->save();
        $document->logs()->create([
            'user_id' => $request->user()->id,
            'action' => 'Document updated',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('compliance.document_acknowledgements.show', $document)
            ->with('success', __('Document acknowledgement updated successfully.'));
    }

    public function download(DocumentAcknowledgement $document)
    {
        $this->authorize('view', $document);

        $file = $this->resolveDocumentPdfPath($document);

        if (is_string($file) && file_exists($file)) {
            return response()->download($file, $document->original_file_name ?: Str::slug($document->title) . '.pdf');
        }

        return abort(404);
    }

    public function assign(Request $request, DocumentAcknowledgement $document)
    {
        $this->authorize('update', $document);

        $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ]);

        $users = $request->input('user_ids');

        foreach (array_unique($users) as $userId) {
            $assignment = $document->assignments()->firstOrCreate([
                'user_id' => $userId,
            ], [
                'status' => 'Pending',
                'ip_address' => null,
                'user_agent' => null,
            ]);

            if ($assignment->wasRecentlyCreated) {
                $assignment->user->notify(new DocumentAcknowledgementAssigned($assignment));
            }
        }

        $document->logs()->create([
            'user_id' => $request->user()->id,
            'action' => 'Document assigned',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', __('Document acknowledgement assigned successfully.'));
    }

    public function userAssignments(Request $request)
    {
        $assignments = DocumentAcknowledgementAssignment::with('document')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        return view('document_acknowledgements.assignments.index', compact('assignments'));
    }

    public function showAssignment(DocumentAcknowledgementAssignment $assignment)
    {
        $this->authorize('view', $assignment);

        if ($assignment->status === 'Pending') {
            $assignment->update([
                'status' => 'Viewed',
                'viewed_at' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }

        return view('document_acknowledgements.assignments.show', compact('assignment'));
    }

    public function acknowledge(AcknowledgeDocumentAcknowledgementRequest $request, DocumentAcknowledgementAssignment $assignment)
    {
        $this->authorize('acknowledge', $assignment);

        $user = $request->user();
        $signature = $user->contractSignature?->signature;

        if (!$signature) {
            return back()->withErrors(['signature' => __('You must have a saved electronic signature to complete the acknowledgement.')]);
        }

        $pdfService = app(DocumentAcknowledgementPdfService::class);
        $sourcePath = $this->resolveDocumentPdfPath($assignment->document);
        $signedFileName = 'document_acknowledgement_signed_' . $assignment->id . '_' . time() . '.pdf';
        $signedPath = storage_path('app/public/' . $signedFileName);

        $pdfService->signPdf(
            $sourcePath,
            $signature,
            $assignment->document->signature_page,
            (float) $assignment->document->signature_x,
            (float) $assignment->document->signature_y,
            $user->name,
            $signedPath
        );

        $assignment->update([
            'status' => 'Signed',
            'signed_at' => now(),
            'signed_file_path' => $signedFileName,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $assignment->document->logs()->create([
            'user_id' => $user->id,
            'action' => 'Document signed',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $user->notify(new DocumentAcknowledgementSigned($assignment));

        return redirect()->route('document_acknowledgements.assignments.index')
            ->with('success', __('Document acknowledgement completed successfully.'));
    }

    public function downloadAssignment(DocumentAcknowledgementAssignment $assignment)
    {
        $this->authorize('download', $assignment);

        if ($assignment->signed_file_path && Storage::disk('public')->exists($assignment->signed_file_path)) {
            return Storage::disk('public')->download($assignment->signed_file_path, Str::slug($assignment->document->title) . '-signed.pdf');
        }

        $file = $this->resolveDocumentPdfPath($assignment->document);

        if (is_string($file) && file_exists($file)) {
            return response()->download($file, $assignment->document->original_file_name ?: Str::slug($assignment->document->title) . '.pdf');
        }

        return abort(404);
    }

    protected function resolveDocumentPdfPath(DocumentAcknowledgement $document): string
    {
        if ($document->original_file_path && Storage::disk('public')->exists($document->original_file_path)) {
            return Storage::disk('public')->path($document->original_file_path);
        }

        if ($document->content) {
            $pdfService = app(DocumentAcknowledgementPdfService::class);
            $html = '<div style="font-family: sans-serif;">' . $document->content . '</div>';
            $binary = $pdfService->generatePdfFromHtml($html);
            $path = tempnam(sys_get_temp_dir(), 'docack_') . '.pdf';
            file_put_contents($path, $binary);
            return $path;
        }

        abort(404, __('Document source not found.'));
    }
}
