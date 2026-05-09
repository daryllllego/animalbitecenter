<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class NurseSessionController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'nurse_id' => 'required|exists:users,id',
            'password' => 'required'
        ]);

        $nurse = User::findOrFail($request->nurse_id);

        // Verify that the nurse belongs to the same branch as the branch account
        if ($nurse->branch !== auth()->user()->branch && !auth()->user()->is_super_admin) {
            return redirect()->back()->with('error', 'Nurse does not belong to this branch.');
        }

        if (Hash::check($request->password, $nurse->password)) {
            session(['active_nurse_id' => $nurse->id]);
            return redirect()->back()->with('success', "Logged in as {$nurse->name}");
        }

        return redirect()->back()->with('error', 'Invalid password for selected nurse.');
    }

    public function logout()
    {
        session()->forget('active_nurse_id');
        return redirect()->back()->with('success', 'Switched back to Branch Account.');
    }
}
