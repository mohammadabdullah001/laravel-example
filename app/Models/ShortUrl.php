<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShortUrl extends Model
{
    use HasFactory;
    use HasEagerLimit;

    protected $fillable = [
        'domain',
    ];

    public function VisitorCountries(): HasMany
    {
        return $this->hasMany(VisitorCountry::class, 'short_url_id', 'id');
    }

    public function VisitorCities(): HasMany
    {
        return $this->hasMany(VisitorCity::class, 'short_url_id', 'id');
    }
}
