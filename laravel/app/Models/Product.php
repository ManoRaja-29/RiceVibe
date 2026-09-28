<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'pack', 'price', 'image', 'description',
        'gallery', 'specs', 'badge', 'active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'specs' => 'array',
            'price' => 'decimal:2',
            'active' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
