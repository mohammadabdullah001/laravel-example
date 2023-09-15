<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VisitorCount extends Model
{
    use HasFactory;

    protected $fillable = [
        'short_url_id',
        'visit_at',
        'total_count',
    ];
}
