<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Organization;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function index(Organization $organization)
    {
        $members = $organization->members()
                               ->with(['student', 'teacher'])
                               ->orderBy('role')
                               ->orderBy('join_date')
                               ->paginate(20);

        $memberStats = $organization->getMemberCountByStatus();
        $leadershipMembers = $organization->getLeadershipMembers();

        return view('admin.members.index', compact('organization', 'members', 'memberStats', 'leadershipMembers'));
    }

    public function create(Organization $organization)
    {
        $students = Student::orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();
        $periods = $organization->periods()->orderBy('start_date', 'desc')->get();

        return view('admin.members.create', compact('organization', 'students', 'teachers', 'periods'));
    }

    public function store(Request $request, Organization $organization)
    {
        $request->validate([
            'member_type' => 'required|in:student,teacher',
            'student_id' => 'required_if:member_type,student|exists:students,id',
            'teacher_id' => 'required_if:member_type,teacher|exists:teachers,id',
            'role' => ['required', Rule::in(['member', 'secretary', 'treasurer', 'vice_leader', 'leader'])],
            'position' => 'nullable|string|max:100',
            'period' => 'required|string|max:50',
            'notes' => 'nullable|string|max:500'
        ]);

        // Check if already a member
        $memberId = $request->member_type === 'student' ? $request->student_id : $request->teacher_id;
        $memberType = $request->member_type === 'student' ? 'student_id' : 'teacher_id';
        
        $existingMember = $organization->members()
                                       ->where($memberType, $memberId)
                                       ->where('period', $request->period)
                                       ->where('status', 'active')
                                       ->first();

        if ($existingMember) {
            return back()
                ->withInput()
                ->withErrors(['duplicate' => 'This person is already an active member for this period.']);
        }

        $memberData = [
            'role' => $request->role,
            'position' => $request->position,
            'period' => $request->period,
            'notes' => $request->notes,
            'join_date' => now()
        ];

        if ($request->member_type === 'student') {
            $memberData['student_id'] = $request->student_id;
        } else {
            $memberData['teacher_id'] = $request->teacher_id;
        }

        $member = $organization->members()->create($memberData);

        return redirect()
            ->route('admin.organizations.members.index', $organization)
            ->with('success', 'Member added successfully!');
    }

    public function show(Organization $organization, Member $member)
    {
        $member->load(['student', 'teacher', 'organization']);
        
        return view('admin.members.show', compact('organization', 'member'));
    }

    public function edit(Organization $organization, Member $member)
    {
        $students = Student::orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();
        $periods = $organization->periods()->orderBy('start_date', 'desc')->get();

        return view('admin.members.edit', compact('organization', 'member', 'students', 'teachers', 'periods'));
    }

    public function update(Request $request, Organization $organization, Member $member)
    {
        $request->validate([
            'role' => ['required', Rule::in(['member', 'secretary', 'treasurer', 'vice_leader', 'leader'])],
            'position' => 'nullable|string|max:100',
            'status' => ['required', Rule::in(['active', 'inactive', 'alumni', 'suspended'])],
            'period' => 'required|string|max:50',
            'notes' => 'nullable|string|max:500',
            'end_date' => 'nullable|date|after_or_equal:join_date'
        ]);

        $member->update($request->only([
            'role', 'position', 'status', 'period', 'notes', 'end_date'
        ]));

        return redirect()
            ->route('admin.organizations.members.index', $organization)
            ->with('success', 'Member updated successfully!');
    }

    public function destroy(Organization $organization, Member $member)
    {
        $member->delete();

        return redirect()
            ->route('admin.organizations.members.index', $organization)
            ->with('success', 'Member removed successfully!');
    }

    public function promote(Request $request, Organization $organization, Member $member)
    {
        $request->validate([
            'new_role' => ['required', Rule::in(['member', 'secretary', 'treasurer', 'vice_leader', 'leader'])],
            'position' => 'nullable|string|max:100'
        ]);

        $member->promoteToRole($request->new_role, $request->position);

        return back()->with('success', 'Member promoted successfully!');
    }

    public function changeStatus(Request $request, Organization $organization, Member $member)
    {
        $request->validate([
            'status' => ['required', Rule::in(['active', 'inactive', 'alumni', 'suspended'])],
            'end_date' => 'nullable|date|after_or_equal:join_date'
        ]);

        $member->changeStatus($request->status, $request->end_date);

        return back()->with('success', 'Member status updated successfully!');
    }

    public function bulkAction(Request $request, Organization $organization)
    {
        $request->validate([
            'action' => 'required|in:activate,inactivate,promote_leader,demote_member',
            'members' => 'required|array',
            'members.*' => 'exists:members,id'
        ]);

        $members = $organization->members()->whereIn('id', $request->members);

        switch ($request->action) {
            case 'activate':
                $members->update(['status' => 'active', 'end_date' => null]);
                $message = 'Members activated successfully!';
                break;
            case 'inactivate':
                $members->update(['status' => 'inactive', 'end_date' => now()]);
                $message = 'Members inactivated successfully!';
                break;
            case 'promote_leader':
                $members->update(['role' => 'leader']);
                $message = 'Members promoted to leaders successfully!';
                break;
            case 'demote_member':
                $members->update(['role' => 'member', 'position' => null]);
                $message = 'Members demoted to regular members successfully!';
                break;
        }

        return back()->with('success', $message);
    }
}
