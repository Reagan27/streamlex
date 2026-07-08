<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\UserDocument;

use App\Models\EducationDocument;
use App\Models\OtherDocument;
use Illuminate\Support\Facades\Storage;
use Vanguard\User;

class UserDocumentController extends Controller
{
    public function show($idNumber)
    {
        $userDocument = UserDocument::where('id_number', $idNumber)->firstOrFail();
        return view('user-documents.show', compact('userDocument'));
    }

    public function uploadEducation(Request $request, $userId)
    {
        $request->validate([
            'education_doc_name' => 'required|string|max:255',
            'education_doc_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);
        $user = User::findOrFail($userId);
        $path = $request->file('education_doc_file')->store('education_documents', 'public');
        $user->educationDocuments()->create([
            'name' => $request->education_doc_name,
            'path' => $path,
        ]);
        return back()->with('success', 'Education document uploaded successfully.');
    }

    public function uploadOther(Request $request, $userId)
    {
        $request->validate([
            'other_doc_name' => 'required|string|max:255',
            'other_doc_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);
        $user = User::findOrFail($userId);
        $path = $request->file('other_doc_file')->store('other_documents', 'public');
        $user->otherDocuments()->create([
            'name' => $request->other_doc_name,
            'path' => $path,
        ]);
        return back()->with('success', 'Other document uploaded successfully.');
    }
}
