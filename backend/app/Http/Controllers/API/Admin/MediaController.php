<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MediaController extends Controller
{
    /**
     * Display a listing of the media.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'per_page' => 'sometimes|integer|min:1|max:100',
            'page' => 'sometimes|integer|min:1',
            'search' => 'nullable|string|max:255',
            'type' => 'nullable|string|in:image,video,document',
        ]);

        $query = Media::query()
            ->when($request->search, function ($q) use ($request) {
                $search = '%' . $request->search . '%';
                return $q->where('name', 'like', $search)
                    ->orWhere('alt_text', 'like', $search);
            })
            ->when($request->type, function ($q) use ($request) {
                if ($request->type === 'image') {
                    return $q->where('mime_type', 'like', 'image/%');
                } elseif ($request->type === 'video') {
                    return $q->where('mime_type', 'like', 'video/%');
                } else {
                    return $q->whereNotIn('mime_type', ['image/%', 'video/%']);
                }
            })
            ->orderBy('created_at', 'desc');

        $perPage = $request->per_page ?? 24;
        $media = $query->paginate($perPage);

        return response()->json([
            'data' => $media->items(),
            'meta' => [
                'current_page' => $media->currentPage(),
                'last_page' => $media->lastPage(),
                'per_page' => $media->perPage(),
                'total' => $media->total(),
            ],
        ]);
    }

    /**
     * Store newly uploaded files.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'files.*' => [
                'required',
                'file',
                'mimes:jpeg,png,jpg,gif,svg,webp,pdf,mp4,mov,avi',
                'max:10240', // 10MB
            ],
        ]);

        $uploadedFiles = [];
        
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileName = Str::random(40) . '.' . $extension;
                $path = $file->storeAs('media', $fileName, 'public');
                $mimeType = $file->getClientMimeType();
                $size = $file->getSize();

                $media = Media::create([
                    'name' => $originalName,
                    'file_name' => $fileName,
                    'mime_type' => $mimeType,
                    'size' => $size,
                    'path' => $path,
                    'url' => asset('storage/' . $path),
                    'disk' => 'public',
                    'alt_text' => pathinfo($originalName, PATHINFO_FILENAME),
                    'user_id' => $request->user()->id,
                ]);

                $uploadedFiles[] = $media;
            }
        }

        return response()->json([
            'message' => 'Files uploaded successfully',
            'data' => $uploadedFiles,
        ], 201);
    }

    /**
     * Display the specified media.
     *
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Media $media)
    {
        return response()->json([
            'data' => $media,
        ]);
    }

    /**
     * Update the specified media in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Media $media)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'caption' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $media->update($validated);

        return response()->json([
            'message' => 'Media updated successfully',
            'data' => $media->fresh(),
        ]);
    }

    /**
     * Remove the specified media from storage.
     *
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Media $media)
    {
        // Delete the file from storage
        Storage::disk($media->disk)->delete($media->path);
        
        // Delete the record from the database
        $media->delete();

        return response()->json([
            'message' => 'Media deleted successfully',
        ]);
    }
}
