<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use App\Models\Movie;
use App\Models\Plan;
use App\Repositories\MovieRepository;
use App\Services\OMDB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $repo   = new MovieRepository();
        $result = $repo->index($request->only('s'));

        return MovieResource::collection($result)->response();
    }

    /**
     * Import movie from OMDB
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function importMovie(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'imdb_id'    => 'required|string|max:300',
            'rent_from'  => 'nullable|date',
            'rent_to'    => 'nullable|date',
            'rent_price' => 'required|numeric|min:0',
            'plan'       => 'required|string|in:' . implode(',', array_keys(Movie::PLANS)),
        ]);

        if ($validator->fails()) {
            return new JsonResponse([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $omdb      = new OMDB();
        $movieData = $omdb->getMovie(['i' => $request->get('imdb_id')]);

        $collectedData = [
            'title'        => $movieData['Title'] ?? null,
            'release_year' => $movieData['Year'] ?? null,
            'poster'       => $movieData['Poster'] ?? null,
        ];

        $collectedData = array_merge($collectedData, $request->only([
            'rent_from',
            'rent_to',
            'rent_price',
            'plan',
        ]));

        (new MovieRepository())->store($collectedData);

        return new JsonResponse([
            'status'  => 'success',
            'message' => 'Movie imported successfully!',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Movie $movie
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Movie $movie)
    {
        // check if premium user or subscribed to movie
        $customer = Auth::user();

        if ($movie->plan === Movie::PLAN_PREMIUM) {
            //TODO: Check the movie have validity

            // check user have premium plan
            // Or have subscription of the movie
            $currentPlan = $customer->currentPlan();
            if (!$currentPlan || $currentPlan?->type !== Plan::TYPE_PREMIUM) {
                $subscribed = $customer->subscriptions()->where('movie_id', $movie->id)
                    ->where('from', '<', now())
                    ->where('to', '>', now())
                    ->exists();

                if (!$subscribed) {
                    return new JsonResponse([
                        'message' => 'Please subscribe to watch!'
                    ], 403);
                }
            }
        }

        return (new MovieResource($movie))->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function rent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|integer|exist:movies,id',
            'days'     => 'required|integer|min:1',
            'payment'  => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->responseValidatorJson($validator);
        }

        $movie = Movie::find($request->get('movie_id'));

        $customer = $request->user();

        (new MovieRepository())->rent($movie, $customer);

        return new JsonResponse([
            'message' => 'Success!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function movieList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            's' => 'required|string|min:3|max:300',
        ]);

        if ($validator->fails()) {
            return new JsonResponse([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            return Cache::remember('movie_list_' . $request->get('s'), 86400, function () use ($request) {
                $omdb = new OMDB();

                return $omdb->getMovieList($request->only(['s']));
            });
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => 'Failed to fetch data!',
            ], 422);
        }
    }
}
