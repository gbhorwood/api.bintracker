<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\FruitbatTestCase;
use App\Models\User;

class postBinsTest extends FruitbatTestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
        \Artisan::call('passport:install');
        $this->seed();
    }

    /**
     * POST /api/bins
     * 201
     *
     * @return void
     */
    public function test_postBins_201()
    {

        $user = User::find(USER_ONE);

        $request = [
            'name' => 'test bin',
        ];

        $response = $this->actingAs($user, 'api')
                         ->postJson('/api/bins', $request);


        $response->assertStatus(201);

        // test valid swagger
        $this->validateSwagger($response, 'POST', '/api/bins');
    }

    /**
     * POST /api/bins
     * 400
     *
     * @return void
     */
    public function test_postBins_400()
    {

        $user = User::find(USER_ONE);

        $request = [
            'name' => null,
        ];

        $response = $this->actingAs($user, 'api')
                         ->postJson('/api/bins', $request);


        $response->assertStatus(400);
    }
}
