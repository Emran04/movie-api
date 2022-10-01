<?php

namespace App\Repositories;

use App\Models\Movie;

class MovieRepository
{
    public function index(array $filters = [])
    {
        return Movie::query()
            ->when($filters['s'] ?? null, fn($q, $value) => $q->where('title', 'like', '%' . $value . '%'))
            ->orderByDesc('id')->paginate();
    }
}