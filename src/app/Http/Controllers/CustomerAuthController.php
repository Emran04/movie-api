<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidationException;
use App\Models\Plan;
use App\Models\User;
use App\Repositories\CustomerRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CustomerAuthController extends Controller
{
    /**
     * Customer profile
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        $customer = $request->user();
        $plan     = $customer->currentPlan();

        return new JsonResponse([
            'data' => [
                'profile' => $customer,
                'plan'    => $plan,
            ],
        ]);
    }

    /**
     * Customer login
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return $this->responseValidatorJson($validator);
        }

        $user = User::query()
            ->where('type', User::TYPE_CUSTOMER)
            ->where('email', $request->get('email'))
            ->first();

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

        $plan = $user->currentPlan();

        return new JsonResponse([
            'user'  => $user,
            'plan'  => $plan,
            'token' => $user->createToken($request->header('User-Agent'))->plainTextToken,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|max:15',
            'plan'     => 'required|string|in:' . implode(',', array_keys(Plan::TYPES)),
            'months'   => 'required|integer|min:1|max:12',
        ]);

        if ($validator->fails()) {
            return $this->responseValidatorJson($validator);
        }

        DB::beginTransaction();
        try {
            (new CustomerRepository())->register($request->only([
                'name',
                'email',
                'password',
                'plan',
                'months',
            ]));
            DB::commit();

            return new JsonResponse([
                'message' => 'Successfully registered!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = 'Failed to register';
            if ($e instanceof ValidationException) {
                $message = $e->getMessage();
            } else {
                Log::error($e->getMessage(), $e->getTrace());
            }

            return new JsonResponse([
                'message' => $message,
            ], 422);
        }
    }
}
