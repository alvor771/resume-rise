<?php

namespace App\Http\Requests;

use App\Models\Vacancy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVacancyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled by the controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $vacancy = $this->route('vacancy');
        
        return [
            'company_id' => [
                'sometimes',
                'required',
                'exists:companies,id',
                function ($attribute, $value, $fail) use ($vacancy) {
                    // Only admin can change company
                    if (!$this->user()->isAdmin() && $vacancy && $vacancy->company_id != $value) {
                        $fail('You cannot change the company of this vacancy.');
                    }
                },
            ],
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'requirements' => 'sometimes|required|string',
            'responsibilities' => 'sometimes|required|string',
            'employment_type' => [
                'sometimes',
                'required',
                'string',
                Rule::in(array_keys(Vacancy::getEmploymentTypes()))
            ],
            'experience_level' => [
                'sometimes',
                'required',
                'string',
                Rule::in(array_keys(Vacancy::getExperienceLevels()))
            ],
            'min_salary' => 'nullable|numeric|min:0',
            'max_salary' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    $minSalary = $this->input('min_salary');
                    if ($minSalary && $value < $minSalary) {
                        $fail('The maximum salary must be greater than or equal to the minimum salary.');
                    }
                },
            ],
            'location' => 'sometimes|required|string|max:255',
            'remote_available' => 'sometimes|boolean',
            'expires_at' => 'sometimes|required|date|after:today',
            'is_active' => 'sometimes|boolean',
            'is_published' => 'sometimes|boolean',
        ];
    }
}
