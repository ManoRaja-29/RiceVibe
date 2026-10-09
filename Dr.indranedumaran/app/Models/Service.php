<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasSlug;

    public const SECTIONS = ['fertility' => 'Fertility', 'gynaecology' => 'Gynaecology', 'maternity' => 'Pregnancy & Maternity Care'];

    protected $guarded = [];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function scopePublished($q)
    {
        return $q->where('is_published', true)->orderBy('sort_order')->orderBy('id');
    }
}
