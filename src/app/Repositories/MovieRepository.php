<?php

namespace App\Repositories;

use App\Models\Movie;
use Illuminate\Support\Arr;

class MovieRepository
{
    /**
     * List of movies
     * @param array $filters
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(array $filters = [])
    {
        return Movie::query()
            ->when($filters['s'] ?? null, fn($q, $value) => $q->where('title', 'like', '%' . $value . '%'))
            ->orderByDesc('id')->paginate();
    }

    /**
     * Store a movie
     *
     * @param array $attrs
     *
     * @return mixed
     */
    public function store(array $attrs)
    {
        $allowedAttrs = Arr::only($attrs, [
            'title',
            'poster',
            'release_year',
            'rent_from',
            'rent_to',
            'rent_price',
            'plan',
        ]);

        return Movie::create($allowedAttrs);
    }
}