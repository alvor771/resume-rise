<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Company::class);

        $query = Company::with('user');

        // If user is a company, only show their own company
        if ($request->user()->isCompany()) {
            $query->where('user_id', $request->user()->id);
        }

        // Search filter
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        // Sort
        $sortField = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_dir', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $companies = $query->paginate($request->input('per_page', 15));

        return CompanyResource::collection($companies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        $this->authorize('create', Company::class);

        $validated = $request->validated();

        // Handle file upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('companies/logos', 'public');
            $validated['logo'] = $path;
        }

        // Set the user_id to the current user if not admin
        if (!$request->user()->isAdmin()) {
            $validated['user_id'] = $request->user()->id;
            $validated['is_active'] = false; // New companies are inactive by default
        }

        $company = Company::create($validated);

        return new CompanyResource($company);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        $this->authorize('view', $company);
        
        return new CompanyResource($company->load('user', 'vacancies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $this->authorize('update', $company);

        $validated = $request->validated();

        // Handle file upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            
            $path = $request->file('logo')->store('companies/logos', 'public');
            $validated['logo'] = $path;
        }

        // Only admin can update is_active
        if (!$request->user()->isAdmin()) {
            unset($validated['is_active']);
        }

        $company->update($validated);

        return new CompanyResource($company);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $this->authorize('delete', $company);

        // Delete logo if exists
        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }

        $company->delete();

        return response()->noContent();
    }
}
