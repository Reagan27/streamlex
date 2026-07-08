<?php
namespace App\Http\Controllers\Api;

use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FieldActivityDocument;
use App\Http\Resources\FieldActivityDocumentResource;

class FieldActivityDocumentController extends Controller
{
    public function index(Request $request)
    {
        $documents = FieldActivityDocument::paginate(20);
        return FieldActivityDocumentResource::collection($documents);
    }

    public function show($id)
    {
        $document = FieldActivityDocument::findOrFail($id);
        return new FieldActivityDocumentResource($document);
    }

    public function store(Request $request)
{
    try {
        $request->validate([
            'field_activity_id' => 'required|exists:field_activities,id',
            'document' => 'required|file',
            'type' => 'nullable|string',
        ]);

        $file = $request->file('document');

        $allowed = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        if (! $file || ! in_array(strtolower($file->getClientOriginalExtension()), $allowed)) {
            return response()->json([
                'message' => 'The document must be a file of type: pdf, doc, docx, jpg, jpeg, png.'
            ], 422);
        }

        $path = $file->store('field_activity_documents', 'public');

        $fileType = $request->input('type') === 'invoice'
            ? 'invoice'
            : $file->getClientMimeType();

        \Log::info('File stored', ['path' => $path]);

        $document = FieldActivityDocument::create([
            'field_activity_id' => $request->field_activity_id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $fileType,
            'file_size' => $file->getSize(),
            'uploaded_by' => auth()->id() ?? 1,
        ]);

        \Log::info('Database record created', ['id' => $document->id]);

        return new FieldActivityDocumentResource($document);

    } catch (\Throwable $e) {

        \Log::error('UPLOAD FAILED', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}
    public function update(Request $request, $id)
    {
        $document = FieldActivityDocument::findOrFail($id);
        $document->update($request->all());
        return new FieldActivityDocumentResource($document);
    }

    public function destroy($id)
    {
        $document = FieldActivityDocument::findOrFail($id);
        $document->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    public function download($id)
    {
        $document = FieldActivityDocument::findOrFail($id);
        return \Storage::disk('public')->download(
            $document->file_path,
            $document->file_name ?: basename($document->file_path)
        );
    }

    public function view($id)
    {
        $document = FieldActivityDocument::findOrFail($id);

        if (!\Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'Document file not found.');
        }

        $mime = $document->file_type ?: \Storage::disk('public')->mimeType($document->file_path) ?: 'application/octet-stream';
        $fileName = $document->file_name ?: basename($document->file_path);

        return response()->stream(function () use ($document) {
            $stream = \Storage::disk('public')->readStream($document->file_path);
            if (is_resource($stream)) {
                fpassthru($stream);
                fclose($stream);
            }
        }, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . addslashes($fileName) . '"',
        ]);
    }
}
