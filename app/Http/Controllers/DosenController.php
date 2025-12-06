<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\AdvisorSubmission;
use App\Models\Notification;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function dashboard()
    {
        $lecturer = auth()->user()->lecturer;
        
        if (!$lecturer) {
            abort(403, 'Lecturer profile not found.');
        }

        $pendingSubmissions = AdvisorSubmission::where('lecturer_id', $lecturer->id)
            ->where('status', 'pending')
            ->with('student.user')
            ->latest()
            ->get();

        $approvedSubmissions = AdvisorSubmission::where('lecturer_id', $lecturer->id)
            ->where('status', 'approved')
            ->with('student.user')
            ->latest()
            ->get();

        $notifications = auth()->user()->notifications()->unread()->latest()->take(5)->get();

        return view('dosen.dashboard', compact('lecturer', 'pendingSubmissions', 'approvedSubmissions', 'notifications'));
    }

    public function profile()
    {
        $lecturer = auth()->user()->lecturer;
        return view('dosen.profile', compact('lecturer'));
    }

    public function updateProfile(Request $request)
    {
        $lecturer = auth()->user()->lecturer;

        $validated = $request->validate([
            'expertise' => 'nullable|string',
            'specialization' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'quota' => 'required|integer|min:0',
        ]);

        $lecturer->update($validated);

        return redirect()->route('dosen.profile')->with('success', 'Profile updated successfully!');
    }

    public function requests()
    {
        $lecturer = auth()->user()->lecturer;
        
        $submissions = AdvisorSubmission::where('lecturer_id', $lecturer->id)
            ->with('student.user')
            ->latest()
            ->paginate(20);

        return view('dosen.requests', compact('submissions'));
    }

    public function approveSubmission($id)
    {
        $submission = AdvisorSubmission::findOrFail($id);
        $lecturer = auth()->user()->lecturer;

        if ($submission->lecturer_id !== $lecturer->id) {
            abort(403);
        }

        if ($submission->status !== 'pending') {
            return back()->with('error', 'Submission has already been processed.');
        }

        if (!$lecturer->hasAvailableQuota()) {
            return back()->with('error', 'You have reached your quota limit.');
        }

        $submission->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        $lecturer->incrementUsedQuota();

        // Send notification to student
        Notification::create([
            'user_id' => $submission->student->user_id,
            'type' => 'submission_approved',
            'message' => "Pengajuan Anda kepada {$lecturer->user->name} telah disetujui!",
            'data' => [
                'lecturer_name' => $lecturer->user->name,
                'approved_at' => now()->format('d-m-Y H:i:s'),
            ],
        ]);

        return back()->with('success', 'Submission approved successfully!');
    }

    public function rejectSubmission(Request $request, $id)
    {
        $submission = AdvisorSubmission::findOrFail($id);
        $lecturer = auth()->user()->lecturer;

        if ($submission->lecturer_id !== $lecturer->id) {
            abort(403);
        }

        if ($submission->status !== 'pending') {
            return back()->with('error', 'Submission has already been processed.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $submission->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        // Send notification to student
        Notification::create([
            'user_id' => $submission->student->user_id,
            'type' => 'submission_rejected',
            'message' => "Pengajuan Anda kepada {$lecturer->user->name} ditolak. Alasan: {$validated['rejection_reason']}",
            'data' => [
                'lecturer_name' => $lecturer->user->name,
                'rejection_reason' => $validated['rejection_reason'],
                'rejected_at' => now()->format('d-m-Y H:i:s'),
            ],
        ]);

        return back()->with('success', 'Submission rejected.');
    }

    public function students()
    {
        $lecturer = auth()->user()->lecturer;
        
        $students = AdvisorSubmission::where('lecturer_id', $lecturer->id)
            ->where('status', 'approved')
            ->with('student.user')
            ->latest()
            ->get();

        return view('dosen.students', compact('students'));
    }

    public function notifications()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(20);
        return view('dosen.notifications', compact('notifications'));
    }

    public function markNotificationAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->markAsRead();

        return back();
    }
}
