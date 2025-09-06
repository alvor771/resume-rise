<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'title',
        'path',
        'icon',
        'layout',
        'parent_id',
        'order',
        'is_visible',
        'is_guest',
        'is_auth',
        'permissions'
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'is_guest' => 'boolean',
        'is_auth' => 'boolean',
        'permissions' => 'array',
        'order' => 'integer'
    ];

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }
}
