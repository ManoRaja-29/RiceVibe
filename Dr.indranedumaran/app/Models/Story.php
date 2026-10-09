<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function scopePublished($q)
    {
        return $q->where('is_published', true)->orderBy('sort_order')->orderByDesc('id');
    }
}
