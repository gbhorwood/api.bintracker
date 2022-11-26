<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\FruitbatTestCase;
use App\Models\User;

class putBinItemTest extends FruitbatTestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
        \Artisan::call('passport:install');
        $this->seed();
    }

    /**
     * PUT /api/bins/:binid/items/:itemid
     * 201
     *
     * @return void
     */
    public function test_putBinItem_201()
    {

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->put('/api/bins/1/items/1');

        $response->assertStatus(201);

        // test valid swagger
        $this->validateSwagger($response, 'PUT', '/api/bins/{binId}/items/{itemId}');
    }

    /**
     * PUT /api/bins/:binid/items/:itemid
     * itemnotexists
     * 404
     *
     * @return void
     */
    public function test_putBinItem_itemnotexists_404()
    {

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->put('/api/bins/1/items/99999');

        $response->assertStatus(404);
    }

    /**
     * PUT /api/bins/:binid/items/:itemid
     * binnotexists
     * 404
     *
     * @return void
     */
    public function test_putBinItem_binnotexists_404()
    {

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->put('/api/bins/99999/items/1');

        $response->assertStatus(404);
    }

    /**
     * PUT /api/bins/:binid/items/:itemid
     * itemnotmine
     * 404
     *
     * @return void
     */
    public function test_putBinItem_itemnotmine_404()
    {

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->put('/api/bins/1/items/6');

        $response->assertStatus(404);
    }

    /**
     * PUT /api/bins/:binid/items/:itemid
     * binnotmine
     * 404
     *
     * @return void
     */
    public function test_putBinItem_binnotmine_404()
    {

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->put('/api/bins/3/items/1');

        $response->assertStatus(404);
    }
}
