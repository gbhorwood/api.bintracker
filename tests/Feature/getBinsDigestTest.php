<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\FruitbatTestCase;
use App\Models\User;

class getBinsDigestTest extends FruitbatTestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
        \Artisan::call('passport:install');
        $this->seed();
    }

    /**
     * GET /api/bins/digest
     * 200
     *
     * @return void
     */
    public function test_getBinsDigest_200()
    {

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->get('/api/bins/digest');

        $response->assertStatus(200);

        // test valid swagger
        $this->validateSwagger($response, 'GET', '/api/bins/digest');
    }

}
