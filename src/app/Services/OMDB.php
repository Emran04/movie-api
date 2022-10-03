<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use Illuminate\Support\Facades\Http;

class OMDB
{
    private $client;

    private $apiKey;

    public function __construct()
    {
        $baseUrl = config('services.omdb.endpoint');
        $this->apiKey  = config('services.omdb.api_key');
        $this->client = Http::baseUrl($baseUrl);
    }

    /**
     * @param array $filter
     *
     * @return array|mixed|null
     * @throws \Exception
     */
    public function getMovie(array $filter)
    {
        if (!isset($filter['i']) && !isset($filter['t']) && !isset($filter['s'])) {
            throw new ValidationException('IMDB id or title or search field required');
        }

        $data = ['apikey' => $this->apiKey];

        $data = array_merge($data, $filter);

        $response = $this->client->get('/', $data);

        if ($response->ok()) {
            return $response->json();
        }

        return null;
    }

    /**
     * @param array $filter
     *
     * @return array|mixed|null
     * @throws \Exception
     */
    public function getMovieList(array $filter)
    {
        if (!isset($filter['s'])) {
            throw new ValidationException('Search field is required');
        }

        $data = ['apikey' => $this->apiKey];

        $data = array_merge($data, $filter);

        $response = $this->client->get('/', $data);

        if ($response->ok()) {
            return $response->json();
        }

        return null;
    }
}