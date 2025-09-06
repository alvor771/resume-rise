<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;

class TemplateRenderController extends Controller
{
    /**
     * List of valid sections that can be rendered
     */
    protected $validSections = [
        'header', 'experience', 'education', 
        'skills', 'languages', 'summary'
    ];

    /**
     * Render a specific section of the template
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $section
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderSection(Request $request, string $section)
    {
        try {
            // Validate section name
            if (!in_array($section, $this->validSections)) {
                return $this->errorResponse('Invalid section specified', 400);
            }

            // For summary section, handle both direct string and object formats
            if ($section === 'summary') {
                $validatedData = [
                    'content' => $request->input('content') ?? $request->input()
                ];
                
                $viewData = ['cvData' => [
                    'summary' => is_string($validatedData['content']) 
                        ? $validatedData['content'] 
                        : ($validatedData['content']['content'] ?? '')
                ]];
            } else {
                // For other sections, use the normal validation
                $validatedData = $this->validateSectionData($request, $section);
                
                // Prepare data for the view
                $viewData = ['cvData' => []];
                
                // Structure data according to the template's expectations
                if ($section === 'header') {
                    $viewData['cvData']['header'] = $validatedData;
                } else {
                    $viewData['cvData']['sections'] = [
                        ['type' => $section, 'data' => $validatedData]
                    ];
                }
            }

            // Render the appropriate view
            $viewName = "cv.sections.{$section}";
            
            if (!View::exists($viewName)) {
                return $this->errorResponse('Template not found for section: ' . $section, 404);
            }

            $html = view($viewName, $viewData)->render();

            return response()->json([
                'success' => true,
                'section' => $section,
                'html' => $html
            ]);

        } catch (ValidationException $e) {
            return $this->errorResponse('Validation error', 422, $e->errors());
        } catch (\Exception $e) {
            Log::error("Error rendering section {$section}: " . $e->getMessage());
            return $this->errorResponse(
                'Failed to render section', 
                500, 
                config('app.debug') ? $e->getMessage() : null
            );
        }
    }

    /**
     * Render multiple sections in a single request
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderMultipleSections(Request $request)
    {
        try {
            $request->validate([
                'sections' => 'required|array',
                'sections.*' => 'string|in:' . implode(',', $this->validSections),
            ]);

            $sections = $request->input('sections');
            $result = [];

            foreach ($sections as $section) {
                $method = 'render' . ucfirst($section) . 'Section';
                if (method_exists($this, $method)) {
                    $result[$section] = $this->$method($request);
                } else {
                    $result[$section] = [
                        'success' => false,
                        'error' => 'Rendering method not implemented'
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'sections' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Error rendering multiple sections: ' . $e->getMessage());
            return $this->errorResponse('Failed to render sections', 500);
        }
    }

    /**
     * Validate section data based on section type
     */
    protected function validateSectionData(Request $request, string $section)
    {
        $validationRules = [
            'header' => [
                'fullName' => 'required|string|max:100',
                'title' => 'required|string|max:100',
                'email' => 'required|email|max:100',
                'phone' => 'nullable|string|max:20',
                'location' => 'nullable|string|max:255',
                'photo' => 'nullable|string',
            ],
            'experience' => 'required|array',
            'experience.*' => [
                'position' => 'required|string|max:100',
                'company' => 'required|string|max:100',
                'startDate' => 'required|date',
                'endDate' => 'nullable|date|after:startDate',
                'description' => 'nullable|string',
                'location' => 'nullable|string|max:100',
            ],
            'education' => 'required|array',
            'education.*' => [
                'degree' => 'required|string|max:100',
                'institution' => 'required|string|max:100',
                'startDate' => 'required|date',
                'endDate' => 'nullable|date|after:startDate',
                'description' => 'nullable|string',
                'location' => 'nullable|string|max:100',
            ],
            'skills' => 'required|array',
            'skills.*' => [
                'name' => 'required|string|max:50',
                'level' => 'required|integer|min:0|max:100',
                'category' => 'nullable|string|max:50',
            ],
            'languages' => 'required|array',
            'languages.*' => [
                'name' => 'required|string|max:50',
                'proficiency' => 'required|integer|min:1|max:5',
            ],
            'summary' => [
                'content' => 'required|string',
            ],
        ];

        if (!isset($validationRules[$section])) {
            throw new \InvalidArgumentException("No validation rules defined for section: {$section}");
        }

        return $request->validate($validationRules[$section]);
    }

    /**
     * Helper method to format error responses
     */
    protected function errorResponse(string $message, int $status = 400, $errors = null)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }

    // Individual section renderers (can be extended as needed)
    
    protected function renderHeaderSection(Request $request)
    {
        $data = $request->validate([
            'fullName' => 'required|string|max:100',
            'title' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'photo' => 'nullable|string',
        ]);

        return view('cv.sections.header', ['cvData' => ['header' => $data]])->render();
    }

    protected function renderSummarySection(Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string',
        ]);

        return view('cv.sections.summary', ['cvData' => ['summary' => $data['content']]])->render();
    }

    // Add more section-specific renderers as needed

    /**
     * Render the full template
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderFull(Request $request)
    {
        try {
            $cvData = $request->validate([
                'header' => 'required|array',
                'experience' => 'array',
                'education' => 'array',
                'skills' => 'array',
                'languages' => 'array',
                'summary' => 'string|nullable',
            ]);

            return response()->json([
                'success' => true,
                'html' => view('cv.template', $cvData)->render()
            ]);

        } catch (\Exception $e) {
            Log::error('Error rendering full template: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to render template',
                'message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
