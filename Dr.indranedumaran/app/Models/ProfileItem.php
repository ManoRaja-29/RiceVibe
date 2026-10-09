<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileItem extends Model
{
    public const TYPES = ['qualification' => 'Qualification', 'achievement' => 'Achievement', 'timeline' => 'Career timeline', 'membership' => 'Membership / society', 'registration' => 'Registration'];

    protected $guarded = [];

    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'is_placeholder' => 'boolean'];
    }

    public function scopePublished($q)
    {
        return $q->where('is_published', true)->orderBy('sort_order')->orderBy('id');
    }
}
