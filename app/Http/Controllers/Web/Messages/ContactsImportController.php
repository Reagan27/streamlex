<?php

namespace Vanguard\Http\Controllers\Web\Messages;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Contact;
use Vanguard\Group;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ContactsImportController extends Controller
{
    /**
     * Show the import contacts form with the existing groups.
     */
    public function showImportForm()
    {
        $groups = Group::where('type', Group::TYPE_CONTACT)->get();

        return view('messages.import', compact('groups'));
    }

    /**
     * Access Import Contact Template.
     */
    public function downloadTemplate()
    {
        $filePath = public_path('templates/contacts_template.xlsx');
        return response()->download($filePath, 'contacts_template.xlsx');
    }


    /**
     * Import contacts from an Excel or CSV file and assign them to group.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
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
                'type' => Group::TYPE_CONTACT,
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
            if ($key == 0 || empty($row[1])) {
                continue;
            }
    
            $formattedPhone = $this->formatPhoneNumber($row[1]);
    
            if (empty($formattedPhone)) {
                continue;
            }
    
            $email = isset($row[2]) ? trim($row[2]) : null;
    
            Contact::create([
                'name' => trim($row[0]),
                'phone' => $formattedPhone,
                'email' => $email,
                'group_id' => $group->id,
            ]);
        }
    
        return redirect()->back()->with('success', 'Contacts imported successfully!');
    }
    

    public function edit(Contact $contact)
    {
        $groups = Group::where('type', Group::TYPE_CONTACT)->get();
        return view('messages.edit', compact('contact', 'groups'));
    }

    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'group_id' => 'required|exists:groups,id'
        ]);

        $contact->update([
            'name' => $request->input('name'),
            'phone' => $this->formatPhoneNumber($request->input('phone')),
            'email' => $request->input('email'),
            'group_id' => $request->input('group_id')
        ]);

        return redirect()->back()->with('success', 'Contact updated successfully!');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->back()->with('success', 'Contact deleted successfully!');
    }


    /**
     * Convert phone numbers to a standardized format for Kenya.
     *
     * @param string $phone
     * @return string
     */
    public function formatPhoneNumber($phone)
    {    
        if (preg_match('/^0/', $phone)) {
            return '+254' . substr($phone, 1);
        }
    
        if (preg_match('/^254/', $phone)) {
            return '+' . $phone;
        }
    
        if (preg_match('/^\+254/', $phone)) {
            return $phone;
        }

        return $phone;
    }  
}
