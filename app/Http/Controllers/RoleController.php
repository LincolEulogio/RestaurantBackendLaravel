<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of roles
     */
    public function index()
    {
        $roles = Role::all();

        // Add user count to each role
        foreach ($roles as $role) {
            $role->user_count = $role->getUserCountAttribute();
        }

        return view('roles.index', compact('roles'));
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles|alpha_dash',
            'permissions' => 'nullable|array',
        ]);

        // Normalize permissions to booleans
        $permissions = [];
        if (! empty($validated['permissions'])) {
            foreach ($validated['permissions'] as $key => $value) {
                $permissions[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }
        }

        // Ensure all available permissions are set (false if not provided)
        foreach (Role::availablePermissions() as $key => $description) {
            if (! isset($permissions[$key])) {
                $permissions[$key] = false;
            }
        }

        $validated['permissions'] = $permissions;

        Role::create($validated);

        return redirect()->route('roles.index')->with('success', 'Rol creado correctamente.');
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, Role $role)
    {
        // AJAX request for batch permissions update
        if ($request->wantsJson() && $request->has('permissions')) {
            try {
                $permissions = $request->input('permissions');

                \Log::info('Updating permissions for role: '.$role->name, [
                    'role_id' => $role->id,
                    'received_permissions' => $permissions,
                    'current_permissions' => $role->permissions,
                ]);

                // Normalize permissions to booleans
                $normalizedPermissions = [];
                foreach ($permissions as $key => $value) {
                    // Handle both boolean and string values
                    if (is_bool($value)) {
                        $normalizedPermissions[$key] = $value;
                    } else {
                        $normalizedPermissions[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                    }
                }

                \Log::info('Normalized permissions', ['normalized' => $normalizedPermissions]);

                // Direct assignment and save
                $role->permissions = $normalizedPermissions;
                $saved = $role->save();

                // Refresh from database to confirm
                $role->refresh();

                \Log::info('Save result', [
                    'saved' => $saved,
                    'after_refresh_permissions' => $role->permissions,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Permisos actualizados correctamente',
                    'permissions' => $role->permissions,
                    'saved' => $saved,
                ]);
            } catch (\Exception $e) {
                \Log::error('Error updating permissions', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar permisos: '.$e->getMessage(),
                ], 500);
            }
        }

        // Standard form update (name/slug)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|alpha_dash|unique:roles,slug,'.$role->id,
            'permissions' => 'nullable|array',
        ]);

        // Normalize permissions if provided
        if ($request->has('permissions')) {
            $permissions = [];
            foreach ($request->input('permissions') as $key => $val) {
                $permissions[$key] = filter_var($val, FILTER_VALIDATE_BOOLEAN);
            }
            $validated['permissions'] = $permissions;
        }

        $role->update($validated);

        return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente.');
    }

    /**
     * Remove the specified role
     */
    public function destroy(Role $role)
    {
        // Prevent deletion of admin role
        if ($role->slug === 'admin') {
            return redirect()->route('roles.index')
                ->with('error', 'No se puede eliminar el rol de administrador.');
        }

        // Check if role has users
        $userCount = \App\Models\User::where('role', $role->slug)->count();
        if ($userCount > 0) {
            return redirect()->route('roles.index')
                ->with('error', "No se puede eliminar el rol porque tiene {$userCount} usuario(s) asignado(s).");
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Rol eliminado correctamente.');
    }
}
