<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\FruitbatTestCase;
use App\Models\User;

class postItemsTest extends FruitbatTestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
        \Artisan::call('passport:install');
        $this->seed();
    }

    /**
     * POST /api/items
     * 201
     *
     * @return void
     */
    public function test_postItems_201()
    {

        $user = User::find(USER_ONE);

        $request = [
            'name' => 'sample item',
            'amount' => 1,
            'unit_id' => 1,
        ];

        $response = $this->actingAs($user, 'api')
                         ->postJson('/api/items', $request);

        $response->assertStatus(201);

        // test valid swagger
        $this->validateSwagger($response, 'POST', '/api/items');
    }

    /**
     * POST /api/items
     * 400
     *
     * @return void
     * @dataProvider http400provider
     */
    public function test_postItems_400($name, $amount, $unitId)
    {

        $user = User::find(USER_ONE);

        $request = [
            'name' => $name,
            'amount' => $amount,
            'unit_id' => $unitId,
        ];

        $response = $this->actingAs($user, 'api')
                         ->postJson('/api/items', $request);

        $response->assertStatus(400);
    }

    /**
     * Data provider for 400
     */
    public function http400provider()
    {
        return [
            [null, null, null],
            [7, 1, 2],
            [7, "one", 2],
            [7, 1, "two"],
        ];
    } // http400Provider
}
