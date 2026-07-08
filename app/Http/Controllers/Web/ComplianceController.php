<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Vanguard\ComplianceCategory;
use Vanguard\ComplianceDocument;
use Vanguard\ComplianceRenewalHistory;
use Vanguard\Http\Controllers\Controller;

class ComplianceController extends Controller
{
    public function index(Request $request)
    {
        $query = ComplianceDocument::with('category')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('reference_number', 'like', "%{$search}%")
                    ->orWhere('regulatory_authority', 'like', "%{$search}%")
                    ->orWhere('responsible_officer', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $documents = $query->paginate(15);
        $categories = ComplianceCategory::orderBy('name')->get();
        $expiringSoon = ComplianceDocument::whereNotNull('expiry_date')
            ->whereBetween('expiry_date', [now()->startOfDay(), now()->addDays(30)->endOfDay()])
            ->count();
        $expired = ComplianceDocument::whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<', now()->toDateString())
            ->count();
        $upcoming = ComplianceDocument::whereNotNull('expiry_date')
            ->whereDate('expiry_date', '>=', now()->toDateString())
            ->orderBy('expiry_date')
            ->take(5)
            ->get();

        return view('compliance.index', compact('documents', 'categories', 'expiringSoon', 'expired', 'upcoming'));
    }

    public function create()
    {
        $categories = ComplianceCategory::orderBy('name')->get();

        return view('compliance.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:compliance_categories,id',
            'reference_number' => 'nullable|string|max:255',
            'regulatory_authority' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'responsible_officer' => 'nullable|string|max:255',
            'renewal_frequency' => 'nullable|string|max:50',
            'reminder_period' => 'nullable|integer|min:1',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:issue_date',
            'description' => 'nullable|string',
            'document' => 'nullable|file|max:20480|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('compliance-documents', 'public');
            $data['document_path'] = $path;
            $data['document_name'] = $request->file('document')->getClientOriginalName();
        }

        $data['status'] = $this->determineStatus($data['expiry_date'] ?? null);
        $data['next_renewal_date'] = $data['expiry_date'];
        $data['reminder_period'] = $data['reminder_period'] ?? 30;

        $document = ComplianceDocument::create($data);

        return redirect()->route('compliance.index')->with('success', 'Compliance document created successfully.');
    }

    public function show(ComplianceDocument $compliance)
    {
        $compliance->load('category', 'renewals');

        return view('compliance.show', compact('compliance'));
    }

    public function edit(ComplianceDocument $compliance)
    {
        $categories = ComplianceCategory::orderBy('name')->get();

        return view('compliance.edit', compact('compliance', 'categories'));
    }

    public function update(Request $request, ComplianceDocument $compliance)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:compliance_categories,id',
            'reference_number' => 'nullable|string|max:255',
            'regulatory_authority' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'responsible_officer' => 'nullable|string|max:255',
            'renewal_frequency' => 'nullable|string|max:50',
            'reminder_period' => 'nullable|integer|min:1',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:issue_date',
            'description' => 'nullable|string',
            'document' => 'nullable|file|max:20480|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        if ($request->hasFile('document')) {
            if ($compliance->document_path) {
                Storage::disk('public')->delete($compliance->document_path);
            }

            $path = $request->file('document')->store('compliance-documents', 'public');
            $data['document_path'] = $path;
            $data['document_name'] = $request->file('document')->getClientOriginalName();
        }

        $data['status'] = $this->determineStatus($data['expiry_date'] ?? null);
        $data['next_renewal_date'] = $data['expiry_date'];
        $data['reminder_period'] = $data['reminder_period'] ?? 30;

        $compliance->update($data);

        return redirect()->route('compliance.index')->with('success', 'Compliance document updated successfully.');
    }

    public function destroy(ComplianceDocument $compliance)
    {
        $compliance->delete();

        return redirect()->route('compliance.index')->with('success', 'Compliance document removed successfully.');
    }

    public function download(ComplianceDocument $compliance)
    {
        if (!$compliance->document_path || !Storage::disk('public')->exists($compliance->document_path)) {
            return back()->withErrors('The requested document was not found.');
        }

        return response()->download(Storage::disk('public')->path($compliance->document_path), $compliance->document_name ?? $compliance->name . '.pdf');
    }

    public function renew(Request $request, ComplianceDocument $compliance)
    {
        $data = $request->validate([
            'renewed_on' => 'required|date',
            'notes' => 'nullable|string',
            'document' => 'nullable|file|max:20480|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        $path = null;
        $name = null;
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('compliance-documents', 'public');
            $name = $request->file('document')->getClientOriginalName();
        }

        $compliance->update([
            'status' => $this->determineStatus($compliance->expiry_date),
            'document_path' => $path ?? $compliance->document_path,
            'document_name' => $name ?? $compliance->document_name,
            'last_reminder_at' => null,
            'last_reminder_stage' => null,
        ]);

        $compliance->renewals()->create([
            'renewed_on' => $data['renewed_on'],
            'notes' => $data['notes'] ?? null,
            'document_path' => $path,
            'document_name' => $name,
            'renewal_type' => 'renewal',
        ]);

        return redirect()->route('compliance.show', $compliance)->with('success', 'Renewal recorded successfully.');
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:compliance_categories,name',
            'description' => 'nullable|string',
        ]);

        ComplianceCategory::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
        ]);

        return back()->with('success', 'Compliance category created successfully.');
    }

    private function determineStatus(?string $expiryDate): string
    {
        if (!$expiryDate) {
            return 'active';
        }

        return $expiryDate < now()->toDateString() ? 'expired' : 'active';
    }
}
