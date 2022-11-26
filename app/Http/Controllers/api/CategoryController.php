<?php
namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

use App\Models\Category;

/**
 * CategoryController
 *
 * @author gbh
 */
class CategoryController extends BaseController
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
     * Return digest of categories for making menus
     *
     * @return JsonResponse
     * @see http://swagger-bintracker.cloverhitch.ca/#get-/api/categories/digest
     */
    public function getCategoriesDigest(Request $request):JsonResponse
    {
        $categories = Category::whereMine()->get();
        return $this->sendResponse(200, $categories);
    } // getCategoriesDigest


    /**
     * Returns all the items that belong to a category
     *
     * @param Int  $id  The id of the category
     * @return JsonResponse
     * @see http://swagger-bintracker.cloverhitch.ca/#get-/api/categories/-id-/items
     * @todo This should be paged
     */
    public function getCategoryItems(Int $id, Request $request):JsonResponse
    {
            /**
             * Validate category exists
             * HTTP 404
             */
            $category = Category::whereMine()->find($id);
            if (!$category) {
                return $this->sendError(404, "Cateogry does not exist");
            }
            
            /**
             * Get items
             */
            $items = $category->items;

            /**
             * HTTP 200
             */
            return $this->sendResponse(200, $items);
    } // getCategoryItems
}
