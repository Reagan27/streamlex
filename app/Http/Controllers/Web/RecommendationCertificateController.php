<?php

namespace Vanguard\Http\Controllers\Web;


use Illuminate\Http\Request;
use Vanguard\RecommendationCertificate;
use Vanguard\Http\Controllers\Controller;


class RecommendationCertificateController  extends Controller
{
    public function index()
    {
        $templates = RecommendationCertificate::all();
        return view('recommendation_certificates.index', compact('templates'));
    }

    public function create()
    {
        return view('recommendation_certificates.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:recommendation,certificate',
            'threshold' => 'nullable|numeric|min:0|max:100',
            'threshold_status' => 'boolean',
            'content' => 'required|string',
        ]);

        RecommendationCertificate::create($validatedData);

        return redirect()->route('recommendation_certificates.index')
            ->with('success', 'Template created successfully.');
    }

    public function edit($id)
    {
        $template = RecommendationCertificate::findOrFail($id);
        return view('recommendation_certificates.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $template = RecommendationCertificate::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:recommendation,certificate',
            'threshold' => 'nullable|numeric|min:0|max:100',
            'threshold_status' => 'boolean',
            'content' => 'required|string',
        ]);

        $template->update($validatedData);

        return redirect()->route('recommendation_certificates.index')
            ->with('success', 'Template updated successfully.');
    }

    public function destroy($id)
    {
        $template = RecommendationCertificate::findOrFail($id);
        $template->delete();

        return redirect()->route('recommendation_certificates.index')
            ->with('success', 'Template deleted successfully.');
    }
}