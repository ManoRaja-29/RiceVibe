<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public const TOPICS = ['Fertility', 'Gynaecology', 'Pregnancy & maternity care', 'Menstrual health', 'Menopause', 'Other'];

    public const STATUSES = ['pending' => 'Awaiting reply', 'published' => 'Answered & published', 'rejected' => 'Not published'];

    protected $guarded = [];

    protected function casts(): array
    {
        return ['answered_at' => 'datetime'];
    }

    public function scopePublished($q)
    {
        return $q->where('status', 'published')->whereNotNull('answer')->latest('answered_at');
    }

    /** Public display shows the first name only. */
    public function getAskerFirstNameAttribute(): string
    {
        return explode(' ', trim($this->asker_name))[0] ?: 'Anonymous';
    }
}
