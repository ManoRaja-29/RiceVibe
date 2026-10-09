<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasSlug;

    protected $guarded = [];

    protected string $slugSource = 'name';

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
