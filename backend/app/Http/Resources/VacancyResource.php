<?php

namespace App\Http\Resources;

use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VacancyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'responsibilities' => $this->responsibilities,
            'employment_type' => $this->employment_type,
            'employment_type_label' => $this->employment_type ? Vacancy::getEmploymentTypes()[$this->employment_type] ?? null : null,
            'experience_level' => $this->experience_level,
            'experience_level_label' => $this->experience_level ? Vacancy::getExperienceLevels()[$this->experience_level] ?? null : null,
            'min_salary' => $this->min_salary,
            'max_salary' => $this->max_salary,
            'location' => $this->location,
            'remote_available' => $this->remote_available,
            'expires_at' => $this->expires_at,
            'is_active' => $this->is_active,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'company' => new CompanyResource($this->whenLoaded('company')),
        ];
    }
}
