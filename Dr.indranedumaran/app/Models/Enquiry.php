<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    public const STATUSES = ['new' => 'New', 'read' => 'Read', 'replied' => 'Replied', 'archived' => 'Archived'];

    protected $guarded = [];
}
