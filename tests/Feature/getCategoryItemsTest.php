<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\FruitbatTestCase;
use App\Models\User;

class getCategoryItemsTest extends FruitbatTestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
        \Artisan::call('passport:install');
        $this->seed();
    }

    /**
     * GET /api/categories/:id/items
     * 200
     *
     * @return void
     */
    public function test_getCategoryItems_200()
    {

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->get('/api/categories/1/items');

        $response->assertStatus(200);

        // test valid swagger
        $this->validateSwagger($response, 'GET', '/api/categories/{id}/items');
    }

    /**
     * GET /api/categories/:id/items
     * 404
     *
     * @return void
     */
    public function test_getCategoryItems_404()
    {

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->get('/api/categories/99999/items');

        $response->assertStatus(404);
    }
}
