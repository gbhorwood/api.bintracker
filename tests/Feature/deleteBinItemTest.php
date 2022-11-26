<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\FruitbatTestCase;
use App\Models\User;
use App\Models\BinItem;

class deleteBinItemTest extends FruitbatTestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
        \Artisan::call('passport:install');
        $this->seed();
    }

    /**
     * DELETE /api/binitems/:id
     * 201
     *
     * @return void
     */
    public function test_deleteBinItem_201()
    {

        $initalCount = BinItem::where('user_id', '=', USER_ONE)
            ->where('bin_id', '=', 2)
            ->where('item_id', '=', 1)
            ->count();

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->delete('/api/binitems/4');

        $response->assertStatus(201);

        $postCount = BinItem::where('user_id', '=', USER_ONE)
            ->where('bin_id', '=', 2)
            ->where('item_id', '=', 1)
            ->count();

        // assert record was there before and not afterwards
        $this->assertEquals(1, $initalCount);
        $this->assertEquals(0, $postCount);

        // test valid swagger
        $this->validateSwagger($response, 'DELETE', '/api/binitems/{id}');
    }

    /**
     * DELETE /api/binitems/:id
     * binitemnotexists
     * 404
     *
     * @return void
     */
    public function test_deleteBinItem_binitemnotexists_404()
    {

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->delete('/api/binitems/99999');

        $response->assertStatus(404);
    }

    /**
     * DELETE /api/binitems/:id
     * itemnotmine
     * 404
     *
     * @return void
     */
    public function test_deleteBinItem_itemnotmine_404()
    {

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->delete('/api/binitems/7');

        $response->assertStatus(404);
    }

    /**
     * DELETE /api/binitems/:id
     * binnotmine
     * 404
     *
     * It should not be possible for a user to put an item they do not
     * own into a bin they do own, but we check the error anyway.
     * @return void
     */
    public function test_deleteBinItem_binnotmine_404()
    {

        $user = User::find(USER_ONE);

        $binItem = new BinItem();
        $binItem->id = 100;
        $binItem->user_id = 1;
        $binItem->item_id = 4;
        $binItem->bin_id = 4;
        $binItem->save();

        $response = $this->actingAs($user, 'api')
                         ->delete('/api/binitems/'.$binItem->id);

        $response->assertStatus(404);
    }
}
