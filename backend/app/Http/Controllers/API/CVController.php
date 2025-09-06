<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Log;
use Exception;

class CVController extends Controller
{
    /**
     * Generate PDF from HTML content
     */
    public function generatePDF(Request $request)
    {
        try {
            // Enable error reporting for debugging
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            
            \Log::info('PDF Generation Request:', $request->all());
            
            // Validate the request
            $validated = $request->validate([
                'cvData' => 'required|array',
                'filename' => 'sometimes|string|max:255'
            ]);
            
            $cvData = $validated['cvData'];
            
            // Ensure the data structure is correct
            if (!isset($cvData['header'])) {
                $cvData['header'] = [
                    'fullName' => $cvData['fullName'] ?? 'Your Name',
                    'position' => $cvData['position'] ?? 'Professional Title',
                    'contact' => [
                        'email' => $cvData['email'] ?? '',
                        'phone' => $cvData['phone'] ?? '',
                        'location' => $cvData['location'] ?? '',
                        'website' => $cvData['website'] ?? ''
                    ],
                    'summary' => $cvData['summary'] ?? ''
                ];
                
                // If sections aren't set, try to map from the root level
                if (!isset($cvData['sections'])) {
                    $cvData['sections'] = [];
                    
                    if (isset($cvData['experience']) && is_array($cvData['experience'])) {
                        $cvData['sections'][] = [
                            'type' => 'experience',
                            'data' => $cvData['experience']
                        ];
                    }
                    
                    if (isset($cvData['education']) && is_array($cvData['education'])) {
                        $cvData['sections'][] = [
                            'type' => 'education',
                            'data' => $cvData['education']
                        ];
                    }
                    
                    if (isset($cvData['skills']) && is_array($cvData['skills'])) {
                        $cvData['sections'][] = [
                            'type' => 'skills',
                            'data' => $cvData['skills']
                        ];
                    }
                }
            }
            
            // Log the processed data
            \Log::info('Processed CV Data:', $cvData);
            
            // Ensure the temp directory exists
            $tempDir = storage_path('app/temp');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            // Log the template path for debugging
            $templatePath = resource_path('views/cv/template.blade.php');
            \Log::info('Template path: ' . $templatePath);
            \Log::info('Template exists: ' . (file_exists($templatePath) ? 'Yes' : 'No'));
            
            // Generate PDF with the template and data
            $pdf = PDF::loadView('cv.template', [
                'cvData' => $cvData
            ]);
            
            // Set paper size and orientation
            $pdf->setPaper('A4', 'portrait');
            
            // Generate a unique filename
            // Save the PDF temporarily to check its contents
            $filename = $request->input('filename', 'resume_' . time()) . '.pdf';
            
            // Ensure the temp directory exists
            $tempPdfPath = storage_path('app/temp/' . $filename);
            
            // Ensure the directory exists
            if (!is_dir(dirname($tempPdfPath))) {
                mkdir(dirname($tempPdfPath), 0755, true);
            }
            
            try {
                // Generate PDF with the template and data
                $pdf = PDF::loadView('cv.template', [
                    'cvData' => $cvData
                ]);
                
                // Set paper size and orientation
                $pdf->setPaper('A4', 'portrait');
                
                // Save the PDF to a temporary file
                $pdf->save($tempPdfPath);
                
                // Verify the file was created
                if (!file_exists($tempPdfPath)) {
                    throw new Exception('Failed to create PDF file at: ' . $tempPdfPath);
                }
                
                // Return the PDF as a download response
                return response()->download($tempPdfPath, $filename, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="' . basename($filename) . '"'
                ])->deleteFileAfterSend(true);
                
            } catch (\Exception $e) {
                // Log detailed error information
                $errorMessage = 'PDF Generation Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();
                \Log::error($errorMessage);
                \Log::error('Stack trace: ' . $e->getTraceAsString());
                
                // Check if DomPDF specific error
                if (str_contains($e->getMessage(), 'DOMPDF')) {
                    $errorMessage = 'PDF generation error: ' . $e->getMessage();
                }
                
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate PDF',
                    'error' => $errorMessage,
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => config('app.debug') ? $e->getTraceAsString() : []
                ], 500);
            }
            
        } catch (\Exception $e) {
            // Catch any other exceptions
            \Log::error('Unexpected Error in generatePDF: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            
            // Return a more detailed error response
            return response()->json([
                'success' => false,
                'error' => 'PDF generation failed',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500, [], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Render the CV template with the provided data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function renderTemplate(Request $request)
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

            // Add any additional processing if needed
            
            return response()->json([
                'success' => true,
                'html' => view('cv.template', $cvData)->render()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error rendering template: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'error' => 'Template rendering failed',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500, [], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Render the full CV template with the provided data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function renderFullTemplate(Request $request)
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

            // Prepare the template data with the structure expected by the view
            $viewData = [
                'header' => $cvData['header'] ?? [
                    'fullName' => 'Your Name',
                    'title' => 'Professional Title',
                    'email' => '',
                    'phone' => '',
                    'location' => '',
                    'photo' => null
                ],
                'sections' => []
            ];

            // Add experience section if exists
            if (!empty($cvData['experience'])) {
                $viewData['sections'][] = [
                    'type' => 'experience',
                    'data' => $cvData['experience']
                ];
            }

            // Add education section if exists
            if (!empty($cvData['education'])) {
                $viewData['sections'][] = [
                    'type' => 'education',
                    'data' => $cvData['education']
                ];
            }

            // Add skills section if exists
            if (!empty($cvData['skills'])) {
                $viewData['sections'][] = [
                    'type' => 'skills',
                    'data' => $cvData['skills']
                ];
            }

            // Add languages section if exists
            if (!empty($cvData['languages'])) {
                $viewData['sections'][] = [
                    'type' => 'languages',
                    'data' => $cvData['languages']
                ];
            }

            // Add summary section if exists
            if (!empty($cvData['summary'])) {
                $viewData['sections'][] = [
                    'type' => 'summary',
                    'data' => $cvData['summary']
                ];
            }

            // Debug the view data (remove in production)
            \Log::debug('View Data:', $viewData);

            return response()->json([
                'success' => true,
                'html' => view('cv.template', $viewData)->render()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error rendering full template: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to render template',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred',
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
