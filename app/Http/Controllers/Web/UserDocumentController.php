<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\UserDocument;

class UserDocumentController extends Controller
{
    public function show($idNumber)
    {
        $userDocument = UserDocument::where('id_number', $idNumber)->firstOrFail();
        return view('user-documents.show', compact('userDocument'));
    }
}
