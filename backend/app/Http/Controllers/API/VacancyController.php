<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVacancyRequest;
use App\Http\Requests\UpdateVacancyRequest;
use App\Http\Resources\VacancyResource;
use App\Models\Company;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class VacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Vacancy::class);

        $query = Vacancy::with('company');

        // If user is a company, only show their vacancies
        if ($request->user()->isCompany()) {
            $query->whereHas('company', function($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            });
        }

        // Search filter
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status')) {
            if ($request->input('status') === 'active') {
                $query->active();
            } elseif ($request->input('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Published filter
        if ($request->has('published')) {
            $query->where('is_published', filter_var($request->input('published'), FILTER_VALIDATE_BOOLEAN));
        }

        // Employment type filter
        if ($request->has('employment_type')) {
            $query->where('employment_type', $request->input('employment_type'));
        }

        // Experience level filter
        if ($request->has('experience_level')) {
            $query->where('experience_level', $request->input('experience_level'));
        }

        // Company filter
        if ($request->has('company_id')) {
            $query->where('company_id', $request->input('company_id'));
        }

        // Sort
        $sortField = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_dir', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $vacancies = $query->paginate($request->input('per_page', 15));

        return VacancyResource::collection($vacancies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVacancyRequest $request)
    {
        $this->authorize('create', Vacancy::class);

        $validated = $request->validated();
        
        // Set default values
        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['is_published'] = $validated['is_published'] ?? false;
        $validated['slug'] = Str::slug($validated['title']) . '-' . time();

        $vacancy = Vacancy::create($validated);

        return new VacancyResource($vacancy->load('company'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Vacancy $vacancy)
    {
        $this->authorize('view', $vacancy);
        
        return new VacancyResource($vacancy->load('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVacancyRequest $request, Vacancy $vacancy)
    {
        $this->authorize('update', $vacancy);

        $validated = $request->validated();

        // Only admin can change these fields
        if (!$request->user()->isAdmin()) {
            unset($validated['is_active'], $validated['company_id']);
            
            // Company users can only publish/unpublish their own vacancies
            if ($request->has('is_published') && $vacancy->company->user_id !== $request->user()->id) {
                unset($validated['is_published']);
            }
        }

        // Update slug if title changed
        if ($request->has('title') && $vacancy->title !== $request->input('title')) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . $vacancy->id;
        }

        $vacancy->update($validated);

        return new VacancyResource($vacancy->load('company'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vacancy $vacancy)
    {
        $this->authorize('delete', $vacancy);

        $vacancy->delete();

        return response()->noContent();
    }

    /**
     * Publish the specified vacancy.
     */
    public function publish(Vacancy $vacancy)
    {
        $this->authorize('publish', $vacancy);

        $vacancy->update([
            'is_published' => true,
            'published_at' => now(),
        ]);

        return new VacancyResource($vacancy->load('company'));
    }

    /**
     * Unpublish the specified vacancy.
     */
    public function unpublish(Vacancy $vacancy)
    {
        $this->authorize('publish', $vacancy);

        $vacancy->update([
            'is_published' => false,
        ]);

        return new VacancyResource($vacancy->load('company'));
    }
}
