<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

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
            'password' => $data['password'],
        ]);

        $user->assignRole($data['role']);

        $audit->log('created', 'User', $user->id, [], ['name' => $user->name, 'email' => $user->email, 'role' => $data['role']]);

        return redirect()->route('admin.users.index')->with('success', "Account created for {$user->name}.");
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user, AuditLogService $audit)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:admin,aid_worker',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $old = [
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->roles->first()?->name,
        ];

        $user->update(['name' => $data['name'], 'email' => $data['email']]);

        if (!empty($data['password'])) {
            $user->update(['password' => $data['password']]);
        }

        $user->syncRoles([$data['role']]);

        $audit->log('updated', 'User', $user->id, $old, [
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $data['role'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Account updated for {$user->name}.");
    }

    public function destroy(User $user, AuditLogService $audit)
    {
        $audit->log('deleted', 'User', $user->id, ['name' => $user->name, 'email' => $user->email], []);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User removed.');
    }
}
