<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\AdvisorSubmission;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_dosen' => User::where('role', 'dosen')->count(),
            'total_mahasiswa' => User::where('role', 'mahasiswa')->count(),
            'total_submissions' => AdvisorSubmission::count(),
            'pending_submissions' => AdvisorSubmission::where('status', 'pending')->count(),
            'approved_submissions' => AdvisorSubmission::where('status', 'approved')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users()
    {
        $users = User::with(['lecturer', 'student'])->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function createUser()
    {
        return view('admin.create-user');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,dosen,mahasiswa',
            'nip' => 'required_if:role,dosen',
            'nim' => 'required_if:role,mahasiswa',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        if ($validated['role'] === 'dosen') {
            Lecturer::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'],
                'quota' => 0,
                'used_quota' => 0,
            ]);
        } elseif ($validated['role'] === 'mahasiswa') {
            Student::create([
                'user_id' => $user->id,
                'nim' => $validated['nim'],
                'study_program' => 'Teknik Informatika',
            ]);
        }

        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    public function editUser($id)
    {
        $user = User::with(['lecturer', 'student'])->findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user->update($validated);

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }

    public function lecturers()
    {
        $lecturers = Lecturer::with('user')->paginate(20);
        return view('admin.lecturers', compact('lecturers'));
    }

    public function editLecturerQuota($id)
    {
        $lecturer = Lecturer::with('user')->findOrFail($id);
        return view('admin.edit-lecturer-quota', compact('lecturer'));
    }

    public function updateLecturerQuota(Request $request, $id)
    {
        $lecturer = Lecturer::findOrFail($id);
        $oldQuota = $lecturer->quota;

        $validated = $request->validate([
            'quota' => 'required|integer|min:0',
        ]);

        $lecturer->update(['quota' => $validated['quota']]);

        // Send notification to lecturer
        Notification::create([
            'user_id' => $lecturer->user_id,
            'type' => 'quota_changed',
            'message' => "Kuota Anda telah diubah oleh Admin dari {$oldQuota} menjadi {$validated['quota']}",
            'data' => [
                'old_quota' => $oldQuota,
                'new_quota' => $validated['quota'],
                'changed_at' => now()->format('d-m-Y H:i:s'),
                'changed_by' => auth()->user()->name,
            ],
        ]);

        return redirect()->route('admin.lecturers')->with('success', 'Lecturer quota updated and notification sent!');
    }

    public function submissions()
    {
        $submissions = AdvisorSubmission::with(['student.user', 'lecturer.user'])->latest()->paginate(20);
        return view('admin.submissions', compact('submissions'));
    }
}
