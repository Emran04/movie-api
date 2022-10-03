<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $guarded = [];

    const PLAN_BASIC   = 'basic';
    const PLAN_PREMIUM = 'premium';

    const PLANS = [
        self::PLAN_BASIC   => 'Basic',
        self::PLAN_PREMIUM => 'Premium',
    ];

    public function actors()
    {
        return $this->hasMany(MovieActor::class);
    }
}
