<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class CVController extends Controller
{
    public function save(Request $request)
    {
        $validated = $request->validate([
            'header' => 'required|array',
            'header.fullName' => 'required|string|max:255',
            'header.title' => 'nullable|string|max:255',
            'header.email' => 'nullable|email',
            'header.phone' => 'nullable|string|max:20',
            'header.location' => 'nullable|string|max:255',
            'header.photo' => 'nullable|string',
        ]);

        // Save CV data to database or storage
        $cvData = $request->all();
        $filename = 'cv_' . time() . '.json';
        Storage::disk('local')->put('cv/' . $filename, json_encode($cvData));

        return response()->json([
            'success' => true,
            'message' => 'CV saved successfully',
            'filename' => $filename
        ]);
    }

    /**
     * Render the CV template with the provided data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render(Request $request)
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

    public function export(Request $request)
    {
        try {
            $cvData = $request->all();
            
            // Ensure the cv directory exists
            if (!is_dir(storage_path('app/cv'))) {
                mkdir(storage_path('app/cv'), 0755, true);
            }
            
            // Log the received data for debugging
            \Log::info('CV Export Data:', $cvData);
            
            // Generate PDF with custom settings
            $pdf = PDF::loadView('cv.template', $cvData);
            
            // Set PDF options for full-page rendering
            $pdf->setPaper('a4', 'landscape')
                ->setOptions([
                    'margin-top' => 0,
                    'margin-right' => 0,
                    'margin-bottom' => 0,
                    'margin-left' => 0,
                    // 'dpi' => 300,
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true
                ]);
            
            return $pdf->download('resume.pdf');
            
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error generating PDF: ' . $e->getMessage()
            ], 500);
        }
    }

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
