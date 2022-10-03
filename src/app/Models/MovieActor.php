<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MovieActor extends Pivot
{
    protected $guarded = [];

    public function actor()
    {
        return $this->belongsTo(Actor::class);
    }
}
