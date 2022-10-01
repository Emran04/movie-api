<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use App\Models\Movie;
use App\Repositories\MovieRepository;
use App\Services\OMDB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

        ]);
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
            'imdb_id' => 'required_without:title|string|max:300',
            'title'   => 'required_without:imdb_id|string|max:400',
        ]);

        if ($validator->fails()) {
            return new JsonResponse([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $omdb = new OMDB();
        $movieData = $omdb->getMovie($request->only(['imdb_id', 'title']));

        $collectedData = [
            'title' => $movieData['Title'] ?? null,
            'release_year' => $movieData['Year'] ?? null,
            'poster' => $movieData['Poster'] ?? null,
        ];

        (new MovieRepository())->store($collectedData);

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Movie imported successfully!',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
}
