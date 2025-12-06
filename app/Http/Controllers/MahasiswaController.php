<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\AdvisorSubmission;
use App\Models\Notification;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function dashboard()
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            abort(403, 'Student profile not found.');
        }

        $submissions = AdvisorSubmission::where('student_id', $student->id)
            ->with('lecturer.user')
            ->latest()
            ->get();

        $approvedAdvisor = $submissions->where('status', 'approved')->first();
        $canSubmitMore = $student->canSubmitMore();
        $notifications = auth()->user()->notifications()->unread()->latest()->take(5)->get();

        return view('mahasiswa.dashboard', compact('student', 'submissions', 'approvedAdvisor', 'canSubmitMore', 'notifications'));
    }

    public function browseLecturers(Request $request)
    {
        $query = Lecturer::with('user')->where('quota', '>', 0);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('expertise', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        $lecturers = $query->get();

        return view('mahasiswa.browse-lecturers', compact('lecturers'));
    }

    public function submitRequest(Request $request)
    {
        $student = auth()->user()->student;

        if (!$student->canSubmitMore()) {
            return back()->with('error', 'You have reached the maximum submission limit (3).');
        }

        $validated = $request->validate([
            'lecturer_id' => 'required|exists:lecturers,id',
            'research_topic' => 'required|string',
            'priority' => 'required|integer|min:1|max:3',
        ]);

        // Check if already submitted to this lecturer
        $existing = AdvisorSubmission::where('student_id', $student->id)
            ->where('lecturer_id', $validated['lecturer_id'])
            ->exists();

        if ($existing) {
            return back()->with('error', 'You have already submitted a request to this lecturer.');
        }

        AdvisorSubmission::create([
            'student_id' => $student->id,
            'lecturer_id' => $validated['lecturer_id'],
            'research_topic' => $validated['research_topic'],
            'priority' => $validated['priority'],
            'status' => 'pending',
        ]);

        return redirect()->route('mahasiswa.submissions')->with('success', 'Submission sent successfully!');
    }

    public function submissions()
    {
        $student = auth()->user()->student;
        
        $submissions = AdvisorSubmission::where('student_id', $student->id)
            ->with('lecturer.user')
            ->latest()
            ->get();

        return view('mahasiswa.submissions', compact('submissions'));
    }

    public function notifications()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(20);
        return view('mahasiswa.notifications', compact('notifications'));
    }

    public function markNotificationAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->markAsRead();

        return back();
    }
}
