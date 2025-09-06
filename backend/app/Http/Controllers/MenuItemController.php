<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Data\MenuData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MenuItemController extends Controller
{
    public function index()
    {
        // For testing purposes, return sample menu data
        // return response()->json(MenuData::getSampleMenu());
        
        // In production, you would use the database:
        $menuItems = MenuItem::with('children')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();


        dd($menuItems);

        return response()->json($menuItems);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'path' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'layout' => 'required|string|max:50',
            'parent_id' => 'nullable|exists:menu_items,id',
            'order' => 'integer',
            'is_visible' => 'boolean',
            'is_guest' => 'boolean',
            'is_auth' => 'boolean',
            'permissions' => 'nullable|array',
        ]);

        $menuItem = MenuItem::create($validated);
        return response()->json($menuItem, 201);
    }

    public function show(MenuItem $menuItem)
    {
        return response()->json($menuItem->load('children'));
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'path' => 'sometimes|required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'layout' => 'sometimes|required|string|max:50',
            'parent_id' => 'nullable|exists:menu_items,id',
            'order' => 'sometimes|integer',
            'is_visible' => 'sometimes|boolean',
            'is_guest' => 'sometimes|boolean',
            'is_auth' => 'sometimes|boolean',
            'permissions' => 'nullable|array',
        ]);

        $menuItem->update($validated);
        return response()->json($menuItem);
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();
        return response()->json(null, 204);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menu_items,id',
            'items.*.order' => 'required|integer',
            'items.*.parent_id' => 'nullable|exists:menu_items,id',
        ]);

        foreach ($request->items as $item) {
            MenuItem::where('id', $item['id'])->update([
                'order' => $item['order'],
                'parent_id' => $item['parent_id'] ?? null,
            ]);
        }

        return response()->json(['message' => 'Menu reordered successfully']);
    }
}
