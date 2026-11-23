<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function employees()
    {
        return view('admin.add-edit-employee');
    }

    public function profile()
    {
        $user = Auth::user();

        $admin = Admin::with('user')
            ->where('user_id', $user->user_id)
            ->firstOrFail();

        return view('admin.profile-view', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('Users', 'email')->ignore($user->user_id, 'user_id'),
            ],
            'phone_number' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $profilePicture = $user->profile_picture;

        if ($request->hasFile('profile_picture')) {
            if ($profilePicture) {
                Storage::disk('public')->delete($profilePicture);
            }

            $profilePicture = $request->file('profile_picture')
                ->store('profile-pictures', 'public');
        }

        DB::table('Users')
            ->where('user_id', $user->user_id)
            ->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'] ?? null,
                'location' => $validated['location'] ?? null,
                'profile_picture' => $profilePicture,
            ]);

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Profile updated successfully.');
    }

    public function profileView($id)
    {
        $employee = Employee::with(['user', 'department'])
            ->findOrFail($id);

        return view('admin.profile-view', compact('employee'));
    }

    public function attendance()
    {
        return view('admin.view-attendance');
    }

    public function payroll()
    {
        return view('admin.payroll-management');
    }

    public function scheduleManagement()
    {
        return view('admin.schedule-management');
    }

    public function statistics()
    {
        return view('admin.statistics');
    }
}
