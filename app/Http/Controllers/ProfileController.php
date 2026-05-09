<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // If a nurse is active, show the nurse's profile info instead
        if (session()->has('active_nurse_id')) {
            $nurse = User::find(session('active_nurse_id'));
            if ($nurse) {
                $user = $nurse;
            }
        }
        
        return view('profile.index', [
            'user' => $user,
            'title' => 'Change Password',
            'sidebar' => 'animal-bite',
            'role' => $user->position
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();
        
        // If a nurse is active, update the nurse's password instead
        if (session()->has('active_nurse_id')) {
            $nurse = User::find(session('active_nurse_id'));
            if ($nurse) {
                $user = $nurse;
            }
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password does not match.');
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'plain_password' => $request->new_password
        ]);

        return redirect()->route('profile')->with('success', 'Password updated successfully!');
    }
}
