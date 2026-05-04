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
        
        return view('profile.index', [
            'user' => $user,
            'title' => 'Change Password',
            'sidebar' => 'animal-bite', // Use animal-bite sidebar
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

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password does not match.');
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'plain_password' => $request->new_password // Keeping for compatibility as per previous structure
        ]);

        return redirect()->route('profile')->with('success', 'Password updated successfully!');
    }
}
