<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $fillable = ['type', 'title', 'category', 'product_slug', 'path', 'disk'];
}
