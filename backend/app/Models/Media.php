<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'file_name',
        'mime_type',
        'path',
        'disk',
        'url',
        'size',
        'alt_text',
        'caption',
        'description',
        'user_id',
        'custom_properties',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'custom_properties' => 'array',
        'size' => 'integer',
    ];

    /**
     * Get the user that owns the media.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the file's extension.
     */
    public function getExtensionAttribute(): string
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    /**
     * Get the file's human readable size.
     */
    public function getHumanReadableSizeAttribute(): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        
        for ($i = 0; $this->size > 1024; $i++) {
            $this->size /= 1024;
        }
        
        return round($this->size, 2) . ' ' . $units[$i];
    }

    /**
     * Determine if the file is an image.
     */
    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    /**
     * Determine if the file is a video.
     */
    public function getIsVideoAttribute(): bool
    {
        return str_starts_with($this->mime_type, 'video/');
    }

    /**
     * Get the URL to the file.
     */
    public function getFullUrlAttribute(): string
    {
        return $this->url ?: asset('storage/' . $this->path);
    }
}
