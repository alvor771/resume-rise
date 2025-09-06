<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

class UserRoleController extends Controller
{
    /**
     * Get all roles for a user.
     */
    public function index(User $user)
    {
        $roles = $user->roles;
        return $this->sendResponse($roles, 'User roles retrieved successfully.');
    }

    /**
     * Assign roles to a user.
     */
    public function store(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $user->roles()->sync($request->role_ids);
        
        return $this->sendResponse(
            $user->load('roles'), 
            'Roles assigned to user successfully.'
        );
    }

    /**
     * Remove a role from a user.
     */
    public function destroy(User $user, Role $role)
    {
        // Prevent removing admin role from the last admin
        if ($role->slug === 'admin' && $user->roles()->where('slug', 'admin')->count() <= 1) {
            return $this->sendError('Cannot remove admin role from the last admin user.', [], 403);
        }

        $user->roles()->detach($role->id);
        
        return $this->sendResponse(
            $user->load('roles'), 
            'Role removed from user successfully.'
        );
    }

    /**
     * Get all users with their roles.
     */
    public function usersWithRoles()
    {
        $users = User::with('roles')->get();
        return $this->sendResponse($users, 'Users with roles retrieved successfully.');
    }
}
