<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Template::class);

        $query = Template::query()->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        return response()->json([
            'data' => $query->paginate($request->integer('per_page', 15)),
        ]);
    }

    public function show(Template $template)
    {
        $this->authorize('view', $template);
        return response()->json(['data' => $template->load('author')]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Template::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'key' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('templates', 'key')],
            'description' => ['nullable', 'string', 'max:1000'],
            'category' => ['nullable', 'string', 'max:255'],
            'version' => ['nullable', 'string', 'max:50'],
            'thumbnail' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'is_public' => ['boolean'],
            'is_premium' => ['boolean'],
            'price' => ['numeric', 'min:0'],
        ]);

        $data['author_id'] = $request->user()->id ?? null;

        $template = Template::create($data);

        return response()->json(['data' => $template], 201);
    }

    public function update(Request $request, Template $template)
    {
        $this->authorize('update', $template);

        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'key' => ['sometimes', 'required', 'string', 'max:255', 'alpha_dash', Rule::unique('templates', 'key')->ignore($template->id)],
            'description' => ['nullable', 'string', 'max:1000'],
            'category' => ['nullable', 'string', 'max:255'],
            'version' => ['nullable', 'string', 'max:50'],
            'thumbnail' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'is_public' => ['boolean'],
            'is_premium' => ['boolean'],
            'price' => ['numeric', 'min:0'],
        ]);

        $template->update($data);

        return response()->json(['data' => $template]);
    }

    public function destroy(Template $template)
    {
        $this->authorize('delete', $template);
        $template->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
