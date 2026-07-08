<?php
namespace Vanguard\Http\Controllers\Web\Profile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Models\UserEducationCertificate;
use Illuminate\Support\Facades\Auth;

class EmployeeInfoController extends Controller
{
    public function update(Request $request)
    {
        $authUser = Auth::user();
        $targetUser = $authUser;
        if ($request->filled('user_id') && $authUser->hasRole(['Admin', 'Manager', 'Finance'])) {
            $targetUser = \Vanguard\User::findOrFail($request->user_id);
        }

        // Save basic employee info fields (add more as needed)
        $targetUser->nok_full_name = $request->nok_full_name;
        $targetUser->nok_relationship = $request->nok_relationship;
        $targetUser->nok_mobile = $request->nok_mobile;
        $targetUser->nok_alt_phone = $request->nok_alt_phone;
        $targetUser->nok_email = $request->nok_email;
        $targetUser->nok_address = $request->nok_address;
        $targetUser->marital_status = $request->marital_status;
        $targetUser->spouse_name = $request->spouse_name;
        $targetUser->spouse_contact = $request->spouse_contact;
        $targetUser->dependents = $request->dependents;
        $targetUser->employee_number = $request->employee_number;
        $targetUser->department = $request->department;
        $targetUser->job_title = $request->job_title;
        $targetUser->employment_type = $request->employment_type;
        $targetUser->employment_date = $request->employment_date;
        $targetUser->work_station = $request->work_station;
        $targetUser->supervisor_name = $request->supervisor_name;
        $targetUser->supervisor_title = $request->supervisor_title;
        $targetUser->blood_group = $request->blood_group;
        $targetUser->medical_conditions = $request->medical_conditions;
        $targetUser->allergies = $request->allergies;
        $targetUser->medical_facility = $request->medical_facility;
        $targetUser->disability = $request->disability;
        $targetUser->disability_details = $request->disability_details;
        $targetUser->workplace_adjustments = $request->workplace_adjustments;
        // Statutory & Compliance Declarations
        $targetUser->info_accurate = $request->has('info_accurate');
        $targetUser->info_authorize = $request->has('info_authorize');
        $targetUser->info_falsified = $request->has('info_falsified');
        $targetUser->save();

        // Handle certificate uploads with names
        if ($request->hasFile('certificates')) {
            $names = $request->input('certificate_names', []);
            foreach ($request->file('certificates') as $idx => $file) {
                $path = $file->store('certificates', 'public');
                UserEducationCertificate::create([
                    'user_id' => $targetUser->id,
                    'level' => $names[$idx] ?? null, // Use the provided name as 'level' for display
                    'institution' => null,
                    'award' => null,
                    'year' => null,
                    'file_path' => $path,
                ]);
            }
        }

        // Handle other documents uploads with names
        if ($request->hasFile('other_docs')) {
            $otherNames = $request->input('other_doc_names', []);
            foreach ($request->file('other_docs') as $idx => $file) {
                $path = $file->store('certificates', 'public');
                UserEducationCertificate::create([
                    'user_id' => $targetUser->id,
                    'level' => $otherNames[$idx] ?? null, // Use the provided name as 'level' for display
                    'institution' => null,
                    'award' => null,
                    'year' => null,
                    'file_path' => $path,
                ]);
            }
        }

        if ($targetUser->id !== $authUser->id) {
            return redirect()->route('users.view', $targetUser->id)->withSuccess('Employee Info updated successfully.');
        }
        return redirect()->route('profile')->withSuccess('Employee Info updated successfully.');
    }
}
