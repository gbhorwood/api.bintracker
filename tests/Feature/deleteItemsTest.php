<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\FruitbatTestCase;
use App\Models\User;
use App\Models\Item;

class deleteItemsTest extends FruitbatTestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
        \Artisan::call('passport:install');
        $this->seed();
    }

    /**
     * DELETE /api/items/:id
     * 201
     *
     * @return void
     */
    public function test_deleteItems_201()
    {
        $item = new Item();
        $item->name = "test item";
        $item->amount = 100;
        $item->unit_id = 2;
        $item->user_id = USER_ONE;
        $item->save();
        $itemId = $item->id;

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->delete("/api/items/$itemId");

        $response->assertStatus(201);

        // test valid swagger
        $this->validateSwagger($response, 'DELETE', '/api/items/{id}');
    }

    /**
     * DELETE /api/items/:id
     * notexists
     * 404
     *
     * @return void
     */
    public function test_deleteItems_notexists_404()
    {

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->delete("/api/items/99999");

        $response->assertStatus(404);
    }

    /**
     * DELETE /api/items/:id
     * notmine
     * 404
     *
     * @return void
     */
    public function test_deleteItems_notmine_404()
    {

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->delete("/api/items/6");

        $response->assertStatus(404);
    }

    /**
     * DELETE /api/items/:id
     * inbin
     * 403
     *
     * @return void
     */
    public function test_deleteItems_inbin_403()
    {

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->delete("/api/items/1");

        $response->assertStatus(403);
    }
}
