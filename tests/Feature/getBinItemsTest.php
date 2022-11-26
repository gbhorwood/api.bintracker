<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\FruitbatTestCase;
use App\Models\User;

class getBinItemsTest extends FruitbatTestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
        \Artisan::call('passport:install');
        $this->seed();
    }

    /**
     * GET /api/bins/:id/items
     * 200
     *
     * @return void
     */
    public function test_getBinItems_200()
    {

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->get('/api/bins/1/items');

        $response->assertStatus(200);

        // test valid swagger
        $this->validateSwagger($response, 'GET', '/api/bins/{id}/items');
    }

    /**
     * GET /api/bins/:id/items
     * noitems
     * 200
     *
     * @return void
     */
    public function test_getBinItems_noitems_200()
    {

        $user = User::find(USER_TWO);

        $response = $this->actingAs($user, 'api')
                         ->get('/api/bins/4/items');

        $response->assertStatus(200);

        $this->assertEquals(0, count($this->getBody($response)));

        // test valid swagger
        $this->validateSwagger($response, 'GET', '/api/bins/{id}/items');
    }

    /**
     * GET /api/bins/:id/items
     * notexists
     * 404
     *
     * @return void
     */
    public function test_getBinItems_notexists_404()
    {

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->get('/api/bins/99999/items');

        $response->assertStatus(404);
    }

    /**
     * GET /api/bins/:id/items
     * notmine
     * 404
     *
     * @return void
     */
    public function test_getBinItems_notmine_404()
    {

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->get('/api/bins/6/items');

        $response->assertStatus(404);
    }
}
