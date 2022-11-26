<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\FruitbatTestCase;
use App\Models\User;
use App\Models\CategoryItem;

class deleteCategoryItemTest extends FruitbatTestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
        \Artisan::call('passport:install');
        $this->seed();
    }

    /**
     * DELETE /api/categories/:categoryId/items/:itemId
     * 201
     *
     * @return void
     */
    public function test_deleteCategoryItem_201()
    {
        $beforeCount = CategoryItem::where('category_id', '=', 1)
                        ->where('item_id', '=', 1)
                        ->count();

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->delete('/api/categories/1/items/1');

        $response->assertStatus(201);

        $afterCount = CategoryItem::where('category_id', '=', 1)
                        ->where('item_id', '=', 1)
                        ->count();

        $this->assertEquals(1, $beforeCount);
        $this->assertEquals(0, $afterCount);

        // test valid swagger
        $this->validateSwagger($response, 'DELETE', '/api/categories/{categoryId}/items/{itemId}');
    }

    /**
     * DELETE /api/categories/:categoryId/items/:itemId
     * duplicate
     * 201
     *
     * @return void
     */
    public function test_deleteCategoryItem_duplicate_201()
    {
        $beforeCount = CategoryItem::where('category_id', '=', 1)
                        ->where('item_id', '=', 3)
                        ->count();

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->delete('/api/categories/1/items/3');

        $response->assertStatus(201);

        $afterCount = CategoryItem::where('category_id', '=', 1)
                        ->where('item_id', '=', 3)
                        ->count();

        $this->assertEquals($afterCount, $beforeCount);
    }

    /**
     * DELETE /api/categories/:categoryId/items/:itemId
     * itemnotexists
     * 404
     *
     * @return void
     */
    public function test_deleteCategoryItem_itemnotexists_404()
    {
        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->delete('/api/categories/1/items/99999');

        $response->assertStatus(404);
    }

    /**
     * DELETE /api/categories/:categoryId/items/:itemId
     * categorynotexists
     * 404
     *
     * @return void
     */
    public function test_deleteCategoryItem_categorynotexists_404()
    {
        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->delete('/api/categories/99999/items/3');

        $response->assertStatus(404);
    }
}
