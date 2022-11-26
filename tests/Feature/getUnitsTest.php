<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\FruitbatTestCase;
use App\Models\User;

class getUnitsTest extends FruitbatTestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
        \Artisan::call('passport:install');
        $this->seed();
    }

    /**
     * GET /api/items/units
     * 200
     *
     * @return void
     */
    public function test_getUnits_200()
    {

        $response = $this->get("/api/items/units");

        $response->assertStatus(200);

        $body = $this->getBody($response);

        // test valid swagger
        $this->validateSwagger($response, 'GET', '/api/items/units');
    }

}
