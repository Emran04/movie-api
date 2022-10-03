<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Movie;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $movie    = Movie::factory()->create([
            'plan' => Movie::PLAN_PREMIUM,
        ]);

        Movie::factory()->create([
            'plan' => Movie::PLAN_BASIC,
        ]);

        $customer1 = User::factory()->create([
            'name'  => 'Premium',
            'email' => 'premium@customer.com',
            'type'  => User::TYPE_CUSTOMER,
        ]);

        Plan::factory()->create([
            'user_id' => $customer1->id,
            'type'    => Plan::TYPE_PREMIUM,
            'from'    => now()->subDays(2),
            'to'      => now()->addDays(30),
        ]);

        $customer2 = User::factory()->create([
            'name'  => 'Basic',
            'email' => 'basic@customer.com',
            'type'  => User::TYPE_CUSTOMER,
        ]);

        Plan::factory()->create([
            'user_id' => $customer2->id,
            'type'    => Plan::TYPE_BASIC,
            'from'    => now()->subDays(2),
            'to'      => now()->addDays(30),
        ]);

        Subscription::factory()->create([
            'user_id' => $customer2->id,
            'movie_id' => $movie->id,
            'from'    => now()->subDays(2),
            'to'      => now()->addDays(30),
        ]);

        // Admin
        User::factory()->create([
            'name'  => 'Admin',
            'email' => 'admin@app.com',
            'type'  => User::TYPE_ADMIN,
        ]);
    }
}
