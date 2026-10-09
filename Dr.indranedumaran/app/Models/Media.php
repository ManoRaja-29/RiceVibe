<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media';

    protected $guarded = [];

    public function getUrlAttribute(): string
    {
        return media_url($this->path);
    }

    public function getThumbUrlAttribute(): string
    {
        return media_url($this->thumb_path ?: $this->path);
    }
}
