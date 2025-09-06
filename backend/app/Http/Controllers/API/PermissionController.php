<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions.
     */
    public function index()
    {
        $permissions = Permission::all()->groupBy('group');
        return $this->sendResponse($permissions, 'Permissions retrieved successfully.');
    }

    /**
     * Store a newly created permission.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permissions,slug',
            'description' => 'nullable|string',
            'group' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $permission = Permission::create($request->only(['name', 'slug', 'description', 'group']));
        return $this->sendResponse($permission, 'Permission created successfully.', 201);
    }

    /**
     * Display the specified permission.
     */
    public function show(Permission $permission)
    {
        return $this->sendResponse($permission, 'Permission retrieved successfully.');
    }

    /**
     * Update the specified permission.
     */
    public function update(Request $request, Permission $permission)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'slug')->ignore($permission->id)
            ],
            'description' => 'nullable|string',
            'group' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $permission->update($request->only(['name', 'slug', 'description', 'group']));
        return $this->sendResponse($permission, 'Permission updated successfully.');
    }

    /**
     * Remove the specified permission.
     */
    public function destroy(Permission $permission)
    {
        // Prevent deletion of system permissions
        if ($permission->roles()->count() > 0) {
            return $this->sendError('Cannot delete permission assigned to roles.', [], 403);
        }

        $permission->delete();
        return $this->sendResponse([], 'Permission deleted successfully.');
    }

    /**
     * Get permission groups.
     */
    public function groups()
    {
        $groups = Permission::distinct('group')->pluck('group');
        return $this->sendResponse($groups, 'Permission groups retrieved successfully.');
    }
}
