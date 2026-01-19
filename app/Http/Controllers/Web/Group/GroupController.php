<?php

namespace Vanguard\Http\Controllers\Web\Group;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Group;
use Vanguard\User;
use Vanguard\Contact;
use Vanguard\ImportedEmail;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    /**
     * Display a listing of the groups with search and filtering.
     */
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $query = Group::query();

        if (!$currentUser->isAdmin() && !$currentUser->hasRole('Manager')) {
            if ($currentUser->hasRole('Regional_Coordinator')) {
                $assignedCountyIds = $currentUser->counties()->pluck('id');
                $query->whereHas('users', function ($q) use ($assignedCountyIds) {
                    $q->whereIn('county_id', $assignedCountyIds);
                });
            } elseif ($currentUser->hasRole('County_Coordinator')) {
                $query->whereHas('users', function ($q) use ($currentUser) {
                    $q->where('county_id', $currentUser->county_id);
                });
            }
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $groups = $query->paginate(10);

        return view('groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new group.
     */
    public function create()
    {
        return view('groups.create');
    }

    /**
     * Store a newly created group in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:' . Group::TYPE_CONTACT . ',' . Group::TYPE_EMAIL,
        ]);

        Group::create([
            'name' => $request->input('name'),
            'type' => $request->input('type'),
        ]);

        return redirect()->route('groups.index')->with('success', 'Group created successfully!');
    }

    /**
     * Display the specified group details (either contacts or emails).
     */
    public function show(Group $group, Request $request)
    {
        $membersQuery = $group->type == Group::TYPE_CONTACT 
            ? Contact::where('group_id', $group->id) 
            : ImportedEmail::where('group_id', $group->id);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $membersQuery->where(function ($q) use ($search, $group) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere($group->type == Group::TYPE_CONTACT ? 'phone' : 'recipient', 'like', "%{$search}%");
            });
        }

        $members = $membersQuery->paginate(10);
        return view('groups.show', compact('group', 'members'));
    }

    /**
     * Show the form for editing the specified group.
     */
    public function edit(Group $group)
    {
        return view('groups.edit', compact('group'));
    }

    /**
     * Update the specified group in storage.
     */
    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $group->update([
            'name' => $request->input('name'),
        ]);

        return redirect()->route('groups.index')->with('success', 'Group updated successfully!');
    }

    /**
     * Remove the specified group from storage.
     */
    public function destroy(Group $group)
    {
        $group->delete();

        return redirect()->route('groups.index')->with('success', 'Group deleted successfully!');
    }


    /**
     * Show the form for adding a new member within a group.
     */
    public function createMember($groupId)
    {
        $group = Group::findOrFail($groupId);
        return view('groups.create-member', compact('group'));
    }

    /**
     * Store a newly created member in the specified group.
     */
    public function storeMember(Request $request, $groupId)
    {
        $group = Group::findOrFail($groupId);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => $group->type == Group::TYPE_CONTACT ? 'required|string|max:255' : 'nullable',
            'recipient' => $group->type == Group::TYPE_EMAIL ? 'required|email|max:255' : 'nullable',
        ]);

        $memberData = [
            'name' => $request->input('name'),
            'group_id' => $group->id,
        ];

        if ($group->type == Group::TYPE_CONTACT) {
            $memberData['phone'] = $request->input('phone');
            $memberData['email'] = $request->input('recipient');
        } else {
            $memberData['phone'] = $request->input('phone');
            $memberData['email'] = $request->input('recipient');
        }

        $group->type == Group::TYPE_CONTACT
            ? Contact::create($memberData)
            : ImportedEmail::create($memberData);

        return redirect()->route('groups.show', $group->id)->with('success', 'Member added successfully!');
    }

    /**
     * Show the form for editing a member within a group.
     */
    public function editMember($groupId, $memberId)
    {
        $group = Group::findOrFail($groupId);
        $member = $group->type == Group::TYPE_CONTACT 
            ? Contact::findOrFail($memberId) 
            : ImportedEmail::findOrFail($memberId);

        return view('groups.edit-member', compact('group', 'member'));
    }

    /**
     * Update the specified member's details in storage.
     */
    public function updateMember(Request $request, $groupId, $memberId)
    {
        $group = Group::findOrFail($groupId);
        $member = $group->type == Group::TYPE_CONTACT 
            ? Contact::findOrFail($memberId) 
            : ImportedEmail::findOrFail($memberId);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => $group->type == Group::TYPE_CONTACT ? 'required|string|max:255' : 'nullable',
            'recipient' => $group->type == Group::TYPE_EMAIL ? 'required|email|max:255' : 'nullable',
        ]);

        $updateData = ['name' => $request->input('name')];

        if ($group->type == Group::TYPE_CONTACT) {
            $updateData['phone'] = $request->input('phone');
            $updateData['email'] = $request->input('recipient');
        } else {
            $updateData['phone'] = $request->input('phone');
            $updateData['email'] = $request->input('recipient');
        }

        $member->update($updateData);

        return redirect()->route('groups.show', $group->id)->with('success', 'Member updated successfully!');
    }

    /**
     * Remove the specified member from storage.
     */
    public function destroyMember($groupId, $memberId)
    {
        $group = Group::findOrFail($groupId);
        $member = $group->type == Group::TYPE_CONTACT 
            ? Contact::findOrFail($memberId) 
            : ImportedEmail::findOrFail($memberId);

        $member->delete();

        return redirect()->route('groups.show', $group->id)->with('success', 'Member deleted successfully!');
    }

    /**
     * Download the relevant template (either contacts or emails).
     */
    public function downloadTemplate($type)
    {
        $filePath = public_path('templates/Group Contacts.xlsx');
        $fileName = 'Group Contacts.xlsx';

        if (!in_array($type, ['emails', 'contacts'])) {
            return redirect()->back()->with('error', 'Invalid template type specified.');
        }

        return response()->download($filePath, $fileName);
    }

    /**
     * Search for groups by name or type with advanced search similar to the User search functionality.
     */
    public function search(Request $request)
    {
        $query = Group::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        $groups = $query->paginate(10);

        return response()->json([
            'groups' => $groups,
            'count' => $groups->count(),
            'total' => Group::count(),
            'searchedCount' => $groups->count(),
        ]);
    }
}
