<?php

namespace App\Services;

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

    public function getMovie(array $filter)
    {
        if (!isset($filter['imdb_id']) && !isset($filter['title'])) {
            throw new \Exception('IMDB id or title required');
        }

        $data = ['apikey' => $this->apiKey];

        if (isset($filter['imdb_id'])) {
            $data = array_merge($data, ['i' => $filter['imdb_id']]);
        }

        if (isset($filter['title'])) {
            $data = array_merge($data, ['i' => $filter['title']]);
        }

        $response = $this->client->get('/', $data);

        if ($response->ok()) {
            return $response->json();
        }

        return null;
    }
}