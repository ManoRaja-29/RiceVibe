<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['category_id', 'slug', 'name', 'pack', 'price', 'image_path', 'gallery', 'description', 'specs', 'usage', 'stock', 'badge', 'is_active', 'sort_order'])]
class Product extends Model
{
    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'specs' => 'array',
            'is_active' => 'boolean',
            'price' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
