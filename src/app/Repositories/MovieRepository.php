<?php

namespace App\Repositories;

use App\Exceptions\ValidationException;
use App\Models\Movie;
use App\Models\User;
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

    /**
     * @param \App\Models\Movie $movie
     * @param \App\Models\User $customer
     * @param array $attrs
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \App\Exceptions\ValidationException
     */
    public function rent(Movie $movie, User $customer, array $attrs = [])
    {
        // Check if user have already rented
        $exists = $customer->subscriptions()
            ->where('movie_id', $movie->id)
            ->where('to', '>', now())
            ->orderByDesc('id')
            ->exists();

        if ($exists) {
            throw new ValidationException('You have running subscription for this movie!');
        }

        // Check if movie rent is available for this period
        $duration = $attrs['days'] ?? 1;

        return $customer->subscriptions()->create([
            'movie_id' => $movie->id,
            'from' => now(),
            'to' => now()->addDays($duration)
        ]);
    }
}