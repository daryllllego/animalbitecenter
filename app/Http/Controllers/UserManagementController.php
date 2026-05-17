<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::orderBy('last_name')->get();
        $branches = [
            'Mandaue Branch',
            'Lapu-Lapu Branch',
            'Balamban Branch',
            'Talisay Branch',
            'Bogo Branch',
            'Tubigon Branch',
            'Guadalupe Branch',
            'Inabanga Branch',
            'Tagbilaran Branch',
            'Talibon Branch',
            'Camotes Branch',
            'Consolacion Branch',
            'Carmen Branch',
            'Panglao Branch',
            'Liloan Branch',
            'Jagna Branch',
            'Ubay Branch',
            'Carreta Branch',
            'Admin Head',
            'HR Office',
            'Assistant Admin',
            'All Branches'
        ];

        return view('animalbite.user-management', [
            'users' => $users,
            'branches' => $branches,
            'title' => 'User Management',
            'sidebar' => 'animal-bite',
            'role' => auth()->user()->position
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:5',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'branch' => 'required|string',
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_initial' => $request->middle_initial,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'plain_password' => $request->password,
            'branch' => $request->branch,
            'position' => 'Staff', // Default position for new accounts
            'status' => true,
        ]);

        return redirect()->route('animal-bite.users.index')->with('success', 'User account created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:5',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'branch' => 'required|string',
            'password' => 'nullable|string|min:8',
        ]);

        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_initial' => $request->middle_initial,
            'email' => $request->email,
            'branch' => $request->branch,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
            $data['plain_password'] = $request->password;
        }

        $user->update($data);

        return redirect()->route('animal-bite.users.index')->with('success', 'User account updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('animal-bite.users.index')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('animal-bite.users.index')->with('success', 'User account deleted successfully.');
    }
}
