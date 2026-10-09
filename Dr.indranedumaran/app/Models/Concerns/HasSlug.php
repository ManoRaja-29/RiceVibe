<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::saving(function ($model) {
            $source = $model->slugSource ?? 'title';
            if (empty($model->slug) && ! empty($model->{$source})) {
                $model->slug = static::uniqueSlug(Str::slug($model->{$source}) ?: 'item', $model->getKey());
            } elseif (! empty($model->slug)) {
                $slug = Str::slug($model->slug);
                $model->slug = ($slug !== $model->getOriginal('slug'))
                    ? static::uniqueSlug($slug, $model->getKey())
                    : $slug;
            }
        });
    }

    protected static function uniqueSlug(string $base, $ignoreId = null): string
    {
        $slug = $base;
        $i = 2;
        while (static::query()->where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
