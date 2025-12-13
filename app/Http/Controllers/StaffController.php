<?php

namespace App\Http\Controllers;

class StaffController extends Controller
{
    public function index()
    {
        $users = \App\Models\User::latest()->paginate(10);
        $roles = \App\Models\Role::all(); // Fetch actual roles

        $totalUsers = \App\Models\User::count();
        $adminCount = \App\Models\User::where('role', 'admin')->count();
        $chefCount = \App\Models\User::where('role', 'chef')->count();
        $waiterCount = \App\Models\User::where('role', 'waiter')->count();

        return view('staff.index', compact('users', 'roles', 'totalUsers', 'adminCount', 'chefCount', 'waiterCount'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|exists:roles,slug',
        ]);

        \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('staff.index')->with('success', 'Usuario creado correctamente.');
    }

    public function update(\Illuminate\Http\Request $request, string $id)
    {
        $user = \App\Models\User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'role' => 'required|string|exists:roles,slug',
            'password' => 'nullable|string|min:8',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (! empty($validated['password'])) {
            $user->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('staff.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();

        return redirect()->route('staff.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
