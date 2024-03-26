<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VisitorCountry extends Model
{

    use HasFactory;

    protected $fillable = [
        'short_url_id',
        'country',
        'visit_at',
        'total_count',
    ];
}
