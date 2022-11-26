<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\FruitbatTestCase;
use App\Models\User;
use App\Models\CategoryItem;

class putCategoryItemTest extends FruitbatTestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
        \Artisan::call('passport:install');
        $this->seed();
    }

    /**
     * PUT /api/categories/:categoryId/items/:itemId
     * 201
     *
     * @return void
     */
    public function test_putCategoryItem_201()
    {
        $beforeCount = CategoryItem::where('category_id', '=', 1)
                        ->where('item_id', '=', 3)
                        ->count();

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->put('/api/categories/1/items/3');

        $response->assertStatus(201);

        $afterCount = CategoryItem::where('category_id', '=', 1)
                        ->where('item_id', '=', 3)
                        ->count();

        $this->assertEquals(0, $beforeCount);
        $this->assertEquals(1, $afterCount);

        // test valid swagger
        $this->validateSwagger($response, 'PUT', '/api/categories/{categoryId}/items/{itemId}');
    }

    /**
     * PUT /api/categories/:categoryId/items/:itemId
     * duplicate
     * 201
     *
     * @return void
     */
    public function test_putCategoryItem_duplicate_201()
    {
        $beforeCount = CategoryItem::where('category_id', '=', 1)
                        ->where('item_id', '=', 1)
                        ->count();

        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->put('/api/categories/1/items/1');

        $response->assertStatus(201);

        $afterCount = CategoryItem::where('category_id', '=', 1)
                        ->where('item_id', '=', 1)
                        ->count();

        $this->assertEquals($afterCount, $beforeCount);
    }

    /**
     * PUT /api/categories/:categoryId/items/:itemId
     * itemnotexists
     * 404
     *
     * @return void
     */
    public function test_putCategoryItem_itemnotexists_404()
    {
        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->put('/api/categories/1/items/99999');

        $response->assertStatus(404);
    }

    /**
     * PUT /api/categories/:categoryId/items/:itemId
     * categorynotexists
     * 404
     *
     * @return void
     */
    public function test_putCategoryItem_categorynotexists_404()
    {
        $user = User::find(USER_ONE);

        $response = $this->actingAs($user, 'api')
                         ->put('/api/categories/99999/items/3');

        $response->assertStatus(404);
    }
}
