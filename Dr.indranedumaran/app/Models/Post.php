<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasSlug;

    protected $guarded = [];

    protected function casts(): array
    {
        return ['published_at' => 'datetime', 'is_featured' => 'boolean'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** Published = status published AND publish date reached (this enables scheduling). */
    public function scopePublished($q)
    {
        return $q->where('status', 'published')->where(fn ($w) => $w->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    public function getIsLiveAttribute(): bool
    {
        return $this->status === 'published' && (! $this->published_at || $this->published_at->lte(now()));
    }

    public function getImageUrlAttribute(): ?string
    {
        return media_url($this->featured_image);
    }
}
