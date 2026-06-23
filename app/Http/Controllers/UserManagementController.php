<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->orderBy('name')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request, AuditLogService $audit)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:admin,aid_worker',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole($data['role']);

        $audit->log('created', 'User', $user->id, [], ['name' => $user->name, 'email' => $user->email, 'role' => $data['role']]);

        return redirect()->route('admin.users.index')->with('success', "Account created for {$user->name}.");
    }

    public function destroy(User $user, AuditLogService $audit)
    {
        $audit->log('deleted', 'User', $user->id, ['name' => $user->name, 'email' => $user->email], []);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User removed.');
    }
}
