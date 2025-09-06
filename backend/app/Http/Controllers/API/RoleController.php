<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return $this->sendResponse($roles, 'Roles retrieved successfully.');
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
            'slug' => 'required|string|max:255|unique:roles,slug',
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $role = Role::create($request->only(['name', 'slug', 'description']));
        
        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return $this->sendResponse($role->load('permissions'), 'Role created successfully.', 201);
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role)
    {
        return $this->sendResponse($role->load('permissions'), 'Role retrieved successfully.');
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($role->id)
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'slug')->ignore($role->id)
            ],
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $role->update($request->only(['name', 'slug', 'description']));
        
        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return $this->sendResponse($role->load('permissions'), 'Role updated successfully.');
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role)
    {
        // Prevent deletion of admin role
        if ($role->slug === 'admin') {
            return $this->sendError('Cannot delete admin role.', [], 403);
        }

        $role->delete();
        return $this->sendResponse([], 'Role deleted successfully.');
    }

    /**
     * Get all permissions.
     */
    public function permissions()
    {
        $permissions = Permission::all()->groupBy('group');
        return $this->sendResponse($permissions, 'Permissions retrieved successfully.');
    }
}
