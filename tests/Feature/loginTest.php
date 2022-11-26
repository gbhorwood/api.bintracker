<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\FruitbatTestCase;
use App\Models\User;

class loginTest extends FruitbatTestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
        \Artisan::call('passport:install');
        $this->seed();
    }

    /**
     * GET /api/login
     * 201
     *
     * @return void
     */
    public function test_login_201()
    {

        $request = [
            'email' => 'one@example.ca',
            'password' => '55555',
        ];

        $response = $this->postJson('/api/login', $request);

        $response->assertStatus(201);

        // test valid swagger
        $this->validateSwagger($response, 'POST', '/api/login');
    }

    /**
     * GET /api/login
     * 403
     *
     * @return void
     */
    public function test_login_403()
    {

        $request = [
            'email' => 'one@example.ca',
            'password' => 'not55555',
        ];

        $response = $this->postJson('/api/login', $request);

        $response->assertStatus(403);
    }
}
