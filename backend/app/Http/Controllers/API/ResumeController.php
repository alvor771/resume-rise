<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ResumeController extends Controller
{
    /**
     * Get available resume templates
     *
     * @return JsonResponse
     */
    public function getTemplates(): JsonResponse
    {
        return response()->json([
            'templates' => [
                [
                    'id' => 'professional',
                    'name' => 'Professional',
                    'description' => 'Clean and modern design suitable for all industries',
                    'preview' => '/images/templates/professional-preview.jpg',
                    'sections' => ['summary', 'experience', 'education', 'skills', 'languages']
                ],
                [
                    'id' => 'creative',
                    'name' => 'Creative',
                    'description' => 'Modern design with a creative touch for designers and artists',
                    'preview' => '/images/templates/creative-preview.jpg',
                    'sections' => ['summary', 'experience', 'education', 'skills', 'projects', 'languages']
                ],
                [
                    'id' => 'minimalist',
                    'name' => 'Minimalist',
                    'description' => 'Simple and clean layout that focuses on content',
                    'preview' => '/images/templates/minimalist-preview.jpg',
                    'sections' => ['summary', 'experience', 'education', 'skills']
                ]
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // TODO: Implement resume saving logic
        return response()->json([
            'message' => 'Resume saved successfully',
            'data' => $request->all()
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // TODO: Implement fetching a single resume
        return response()->json([
            'id' => $id,
            'title' => 'Sample Resume',
            'template' => 'professional',
            'content' => []
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // TODO: Implement resume update logic
        return response()->json([
            'message' => 'Resume updated successfully',
            'id' => $id,
            'data' => $request->all()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // TODO: Implement resume deletion logic
        return response()->json([
            'message' => 'Resume deleted successfully',
            'id' => $id
        ], 200);
    }
}