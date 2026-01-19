<?php

namespace Vanguard\Http\Controllers\Web\Emails;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\ImportedEmail;
use Vanguard\Group;
use PhpOffice\PhpSpreadsheet\IOFactory;

class EmailImportController extends Controller
{
    /**
     * Show the import emails form with the existing groups.
     */
    public function showImportForm()
    {
        $groups = Group::where('type', Group::TYPE_EMAIL)->get();
        return view('emails.import', compact('groups'));
    }
    
    /**
     * Access Import Email Template.
     */
    public function downloadTemplate()
    {
        $filePath = public_path('templates/emails_template.xlsx');
        return response()->download($filePath, 'emails_template.xlsx');
    }

    /**
     * Import emails from an Excel or CSV file and assign them to a group.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv|max:2048',
            'group' => 'nullable|string',
            'new_group' => 'nullable|string|required_without:group',
        ]);
    
        if ($request->filled('group')) {
            $group = Group::find($request->group);
    
            if (!$group) {
                return redirect()->back()->with('error', 'Group not found.');
            }
        } else {
            $group = Group::create([
                'name' => $request->new_group,
                'type' => Group::TYPE_EMAIL,
            ]);
        }
    
        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to read the file. Please ensure it is in the correct format.');
        }
    
        foreach ($rows as $key => $row) {
            if ($key == 0 || empty($row[1]) || empty($row[2])) {
                continue;
            }
    
            $name = trim($row[0]);
            $phone = trim($row[1]);
            $email = trim($row[2]);
    
            ImportedEmail::create([
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'group_id' => $group->id,
            ]);
        }
    
        return redirect()->back()->with('success', 'Emails imported successfully!');
    }
    

    public function edit(ImportedEmail $email)
    {
        $groups = Group::where('type', Group::TYPE_EMAIL)->get();
        return view('emails.edit', compact('email', 'groups'));
    }

    public function update(Request $request, ImportedEmail $email)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'email' => 'required|email|max:255|unique:imported_emails,email,' . $email->id,
            'group_id' => 'required|exists:groups,id',
        ]);

        $email->update([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'group_id' => $request->input('group_id'),
        ]);

        return redirect()->route('emails.edit', $email->id)->with('success', 'Email updated successfully!');
    } 
    
    public function destroy(ImportedEmail $email)
    {
        $email->delete();
        return redirect()->back()->with('success', 'Email deleted successfully!');
    }
    
}