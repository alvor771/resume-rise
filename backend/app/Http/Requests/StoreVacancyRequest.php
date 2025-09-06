<?php

namespace App\Http\Requests;

use App\Models\Vacancy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVacancyRequest extends FormRequest
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
        return [
            'company_id' => [
                'required',
                'exists:companies,id',
                function ($attribute, $value, $fail) {
                    // Only admin can create vacancies for any company
                    if (!$this->user()->isAdmin()) {
                        $company = \App\Models\Company::find($value);
                        if (!$company || $company->user_id !== $this->user()->id) {
                            $fail('You can only create vacancies for your own company.');
                        }
                    }
                },
            ],
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'responsibilities' => 'required|string',
            'employment_type' => [
                'required',
                'string',
                Rule::in(array_keys(Vacancy::getEmploymentTypes()))
            ],
            'experience_level' => [
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
                    if ($this->input('min_salary') && $value < $this->input('min_salary')) {
                        $fail('The maximum salary must be greater than or equal to the minimum salary.');
                    }
                },
            ],
            'location' => 'required|string|max:255',
            'remote_available' => 'boolean',
            'expires_at' => 'required|date|after:today',
            'is_active' => 'sometimes|boolean',
            'is_published' => 'sometimes|boolean',
        ];
    }
}
