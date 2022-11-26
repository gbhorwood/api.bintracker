<?php
namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

use App\Models\Item;
use App\Models\Category;
use App\Models\CategoryItem;

/**
 * CategoryItemController
 *
 * @author gbh
 */
class CategoryItemController extends BaseController
{
    /**
     * Utilities object
     * @see \App\Libs\Utilities
     */
    protected $utilities;

    /**
     * Constructor
     *
     * @param \App\Libs\Utilities $utilities The utilities lib object dependency
     */
    public function __construct(\App\Libs\Utilities $utilities) {
        $this->utilities = $utilities;
    }

    /**
     * Put item into category.
     * Creates a CategoryItem. This relationship is what 'tags' an item as being 'in' 
     * a category
     *
     * @param  Int  $categoryId  The id of the category
     * @param  Int  $itemId      The id of the item
     * @return JsonResponse
     * @see http://swagger-bintracker.cloverhitch.ca/#put-/api/categories/-categoryId-/items/-itemId-
     */
    public function putCategoryItem(Int $categoryId, Int $itemId, Request $request):JsonResponse
    {
        /**
         * Validate item exists and is owned by session user
         * HTTP 404
         */
        $item = Item::whereMine()->find($itemId);
        if (!$item) {
            return $this->sendError(404, "Item does not exist");
        }

        /**
         * Validate category exists and is owned by session user
         * HTTP 404
         */
        $category = Category::whereMine()->find($categoryId);
        if (!$category) {
            return $this->sendError(404, "Category does not exist");
        }

        /**
         * Avoid duplicate entries
         * HTTP 201
         */
        $categoryItemTest = CategoryItem::where('category_id', '=', $categoryId)
            ->where('item_id', '=', $itemId)
            ->count();
        if ($categoryItemTest > 0) {
            return $this->sendResponse(201, null);
        }

        /**
         * Create category/item relationship
         */
        $categoryItem = new CategoryItem();
        $categoryItem->category_id = $categoryId;
        $categoryItem->item_id = $itemId;
        $categoryItem->save();

        /**
         * HTTP 201
         */
        return $this->sendResponse(201, null);
    } // putCategoryItem


    /**
     * Delete item from category.
     * Deletes a CategoryItem. This 'untags' and item from a category.
     *
     * @param  Int  $categoryId  The id of the category
     * @param  Int  $itemId      The id of the item
     * @return JsonResponse
     * @see http://swagger-bintracker.cloverhitch.ca/#delete-/api/categories/-categoryId-/items/-itemId-
     */
    public function deleteCategoryItem(Int $categoryId, Int $itemId, Request $request):JsonResponse
    {
        /**
         * Validate item exists and is owned by session user
         * HTTP 404
         */
        $item = Item::whereMine()->find($itemId);
        if (!$item) {
            return $this->sendError(404, "Item does not exist");
        }

        /**
         * Validate category exists and is owned by session user
         * HTTP 404
         */
        $category = Category::whereMine()->find($categoryId);
        if (!$category) {
            return $this->sendError(404, "Category does not exist");
        }

        /**
         * CategoryItem not exists
         * HTTP 201
         */
        $categoryItemTest = CategoryItem::where('category_id', '=', $categoryId)
            ->where('item_id', '=', $itemId)
            ->count();
        if ($categoryItemTest == 0) {
            return $this->sendResponse(201, null);
        }

        /**
         * Delete CategoryItem
         */
        CategoryItem::where('category_id', '=', $categoryId)
            ->where('item_id', '=', $itemId)
            ->delete();

        /**
         * HTTP 201
         */
        return $this->sendResponse(201, null);
    } // deleteCategoryItem
}
