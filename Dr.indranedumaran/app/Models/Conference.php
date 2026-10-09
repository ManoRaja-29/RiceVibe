<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Conference extends Model
{
    use HasSlug;

    public const TYPES = ['conference' => 'Conference', 'workshop' => 'Workshop', 'event' => 'Event / CME', 'talk' => 'Invited talk', 'award' => 'Recognition'];

    protected $guarded = [];

    protected function casts(): array
    {
        return ['start_date' => 'date', 'end_date' => 'date', 'photos' => 'array', 'is_placeholder' => 'boolean'];
    }

    public function scopePublished($q)
    {
        return $q->where('status', 'published');
    }

    public function getHighlightListAttribute(): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $this->highlights))));
    }

    public function getDateRangeAttribute(): string
    {
        if (! $this->end_date || $this->end_date->eq($this->start_date)) {
            return $this->start_date->format('j M Y');
        }
        if ($this->start_date->format('mY') === $this->end_date->format('mY')) {
            return $this->start_date->format('j').'–'.$this->end_date->format('j M Y');
        }

        return $this->start_date->format('j M').' – '.$this->end_date->format('j M Y');
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }
}
