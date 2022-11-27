<?php
namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

use App\Models\Unit;
use App\Models\Item;
use App\Models\BinItem;
use App\Models\CategoryItem;

use App\Libs\Utilities;
use App\Libs\Validation;

class ItemController extends BaseController
{
    /**
     * Utilities object
     * @see \App\Libs\Utilities
     */
    protected Utilities $utilities;

    /**
     * Validation object
     * @see \App\Libs\Validation
     */
    protected Validation $validation;

    /**
     * Constructor
     *
     * @param \App\Libs\Utilities  $utilities  The utilities lib object dependency
     * @param \App\Libs\Validation $validation The validation lib object dependency
     */
    public function __construct(Utilities $utilities, Validation $validation) {
        $this->utilities = $utilities;
        $this->validation = $validation;
    }

    /**
     * Return all units, ie. for building menus
     *
     * @return JsonResponse
     * @see http://swagger.bintracker.test/#get-/api/items/units
     */
    public function getUnits(Request $request):JsonResponse
    {
        $units = Unit::all();
        return $this->sendResponse(200, $units);
    } // getUnits


    /**
     * Return one page of items
     *
     * @return JsonResponse
     * @see http://swagger.bintracker.test/#get-/api/items
     */
    public function getItems(Request $request):JsonResponse
    {
        /**
         * Get pagination data from request
         */
        $paginationDataResult = $this->validateAndExtractPaginationData($request);
        if (!is_array($paginationDataResult)) {
            return $paginationDataResult;
        }
        $size = $paginationDataResult['size'];

        /**
         * Get order and sort data from request
         */
        $order = $request->query('order', 'name');
        $sort = $request->query('sort', 'asc');

        /**
         * Get page of items
         */
        $itemsPage = Item::whereMine()
                        ->orderBy($order, $sort)
                        ->paginate($size);
        $itemsItems = $itemsPage->items();

        /**
         * Build pagination hateoas
         */
        $paginationHateoas = $this->createPaginationFromOrm($itemsPage);

        /**
         * HTTP 200
         */
        return $this->sendResponse(200, $itemsItems, $paginationHateoas);
    } // getItems


    /**
     * Creates one item
     *
     * @return JsonResponse
     * @see http://swagger.bintracker.test/#post-/api/items
     */
    public function postItems(Request $request):JsonResponse
    {
        /**
         * Validate request body
         * HTTP 400
         */
        try {
            $this->validation->validateItemRequest($request);
        }
        catch(\Exception $e) {
            return $this->sendError(400, "Validation failures", json_decode($e->getMessage(), true));
        }

        /**
         * Save item
         */
        $itemRequest = $request->all();
        $item = new Item();
        $item->name = $itemRequest['name'];
        $item->amount = $itemRequest['amount'];
        $item->unit_id = $itemRequest['unit_id'];
        $item->image = @$itemRequest['image'];
        $item->user_id = $this->getLoggedInUserId();
        $item->save();
        
        /**
         * Item select back
         */
        $item = Item::find($item->id);

        /**
         * HTTP 201
         */
        return $this->sendResponse(201, $item);
    } // postItem


    /**
     * Deletes one item by its id
     *
     * @param  Int   $id The id of the item to delete
     * @return JsonResponse
     * @see http://swagger.bintracker.test/#delete-/api/items/-id-
     */
    public function deleteItems(Int $id, Request $request):JsonResponse
    {
        /**
         * Validate item exists and is owned by session user
         * HTTP 404
         */
        $item = Item::whereMine()->find($id);
        if (!$item) {
            return $this->sendError(404, "Item does not exist");
        }

        /**
         * Item cannot be in bins. Must remove from all bins before deletion.
         * HTTP 403
         */
        if ($item->bins->count() > 0) {
            return $this->sendError(403, "Cannot delete item that exists in a bin");
        }

        /**
         * Delete item from categories
         */
        CategoryItem::where('item_id', '=', $id)->delete();

        /**
         * Delete Item
         */
        $item->delete();

        /**
         * HTTP 201
         */
        return $this->sendResponse(201, null);
    } // deleteItems
}
