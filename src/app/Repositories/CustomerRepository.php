<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class CustomerRepository
{
    /**
     * Register customer
     *
     * @param array $attrs
     *
     * @return mixed
     */
    public function register(array $attrs)
    {
        $allowedAttrs = Arr::only($attrs, [
            'name',
            'email',
        ]);

        $allowedAttrs['type'] = User::TYPE_CUSTOMER;

        $allowedAttrs['password'] = Hash::make($attrs['password']);

        $customer = User::create($allowedAttrs);

        // Create plan for customer
        $customer->plans()->create([
            'type' => $attrs['plan'],
            'from' => now(),
            'to'   => now()->addMonths($attrs['months']),
        ]);

        return $customer;
    }
}