<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $guarded = [];

    const TYPE_BASIC   = 'basic';
    const TYPE_PREMIUM = 'premium';

    const TYPES = [
        self::TYPE_BASIC   => 'Basic',
        self::TYPE_PREMIUM => 'Premium',
    ];
}
