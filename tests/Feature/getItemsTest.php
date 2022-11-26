<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\FruitbatTestCase;
use App\Models\User;

class getItemsTest extends FruitbatTestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
        \Artisan::call('passport:install');
        $this->seed();
    }

    /**
     * GET /api/items
     * 200
     *
     * @return void
     */
    public function test_getItems_200()
    {

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->get('/api/items');

        $response->assertStatus(200);

        $body = $this->getBody($response);
        $this->assertEquals(4, count($body));

        // test valid swagger
        $this->validateSwagger($response, 'GET', '/api/items');
    }

    /**
     * GET /api/items
     * paged
     * 200
     *
     * @return void
     */
    public function test_getItems_paged_200()
    {

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->get('/api/items?page=1&size=2');

        $response->assertStatus(200);

        $body = $this->getBody($response);
        $this->assertEquals(2, count($body));
    }

    /**
     * GET /api/items
     * paged2
     * 200
     *
     * @return void
     */
    public function test_getItems_paged2_200()
    {

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->get('/api/items?page=2&size=2');

        $response->assertStatus(200);

        $body = $this->getBody($response);
        $this->assertEquals(2, count($body));
    }

    /**
     * GET /api/items
     * badpage
     * 400
     *
     * @return void
     */
    public function test_getItems_badpage_400()
    {

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->get('/api/items?page=notapage&size=2');

        $response->assertStatus(400);
    }

    /**
     * GET /api/items
     * badsize
     * 400
     *
     * @return void
     */
    public function test_getItems_badsize_400()
    {

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->get('/api/items?page=1&size=notasize');

        $response->assertStatus(400);
    }
}
