<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerAuthController extends Controller
{
    public function me(Request $request)
    {
        return new JsonResponse([
            'data' => $request->user(),
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return $this->responseValidatorJson($validator);
        }

        $user = User::query()->where('email', $request->get('email'))->first();

        if (!$user) {
            return new JsonResponse([
                'message' => 'Invalid email or password!',
            ], 422);
        }

        if (!Hash::check($request->get('password'), $user->password)) {
            return new JsonResponse([
                'message' => 'Invalid email or password!',
            ], 422);
        }

        return new JsonResponse([
            'user'  => $user,
            'token' => $user->createToken($request->header('User-Agent'))->plainTextToken,
        ]);
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
        //
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
