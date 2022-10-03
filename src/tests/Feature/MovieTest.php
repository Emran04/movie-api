<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MovieTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_premium_customer_can_watch_any_premium_movie()
    {
        $movie    = Movie::factory()->create([
            'plan' => Movie::PLAN_PREMIUM,
        ]);
        $customer = User::factory()->create([
            'type' => User::TYPE_CUSTOMER,
        ]);

        Plan::factory()->create([
            'user_id' => $customer->id,
            'type'    => Plan::TYPE_PREMIUM,
            'from'    => now()->subDays(2),
            'to'      => now()->addDays(30),
        ]);

        $response = $this->actingAs($customer, 'user')->getJson('/api/movies/' . $movie->id );

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_basic_customer_can_not_watch_any_premium_movie()
    {
        $movie    = Movie::factory()->create([
            'plan' => Movie::PLAN_PREMIUM,
        ]);
        $customer = User::factory()->create([
            'type' => User::TYPE_CUSTOMER,
        ]);

        Plan::factory()->create([
            'user_id' => $customer->id,
            'type'    => Plan::TYPE_BASIC,
            'from'    => now()->subDays(2),
            'to'      => now()->addDays(30),
        ]);

        $response = $this->actingAs($customer, 'user')->getJson('/api/movies/' . $movie->id );

        $response->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_basic_customer_subscribe_can_watch_premium_movie()
    {
        $movie    = Movie::factory()->create([
            'plan' => Movie::PLAN_PREMIUM,
        ]);

        $customer = User::factory()->create([
            'type' => User::TYPE_CUSTOMER,
        ]);

        Subscription::factory()->create([
            'user_id' => $customer->id,
            'movie_id' => $movie->id,
            'from'    => now()->subDays(2),
            'to'      => now()->addDays(30),
        ]);


        $response = $this->actingAs($customer, 'user')->getJson('/api/movies/' . $movie->id );

        $response->assertStatus(200);
    }
}
