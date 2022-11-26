<?php
namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

use App\Models\Bin;
use App\Models\Item;
use App\Models\BinItem;

/**
 * BinItemController
 *
 * @author gbh
 */
class BinItemController extends BaseController
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
     * Put one item into one bin. Creates a BinItem.
     *
     * @param Int  $binId   The id of the bin
     * @param Int  $itemId  The id of the item
     * @author gbh
     * @see http://swagger-bintracker.cloverhitch.ca/#put-/api/bins/-binId-/items/-itemId-
     */
    public function putBinItem(Int $binId, Int $itemId, Request $request):JsonResponse
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
         * Validate bin exists and is owned by session user
         * HTTP 404
         */
        $bin = Bin::whereMine()->find($binId);
        if (!$bin) {
            return $this->sendError(404, "Bin does not exist");
        }

        /**
         * Create bin/item relationship
         */
        $binItem = new BinItem();
        $binItem->bin_id = $binId;
        $binItem->item_id = $itemId;
        $binItem->user_id = $this->getLoggedInUserId();
        $binItem->save();

        /**
         * HTTP 201
         */
        return $this->sendResponse(201, null);
    } // putItemBin


    /**
     * Delete bin item by it's id. 
     * Deleting a bin item deletes the relationship between the bin and the item, thus
     * 'removing' the item from the bin.
     *
     * @param Int  $id  The id of the BinItem
     * @author gbh
     */
    public function deleteBinItem(Int $id, Request $request):JsonResponse
    {
        /**
         * Validate bin item exists
         * HTTP 404
         */
        $binItem = BinItem::find($id);
        if (!$binItem) {
            return $this->sendError(404, "Bin item does not exist");
        }

        /**
         * Validate item exists and is owned by session user
         * HTTP 404
         */
        $item = Item::whereMine()->find($binItem->item_id);
        if (!$item) {
            return $this->sendError(404, "Bin item does not exist");
        }

        /**
         * Validate bin exists and is owned by session user
         * HTTP 404
         */
        $bin = Bin::whereMine()->find($binItem->bin_id);
        if (!$bin) {
            return $this->sendError(404, "Bin item does not exist");
        }

        /**
         * HTTP 201
         */
        $binItem->delete();
        return $this->sendResponse(201, null);
    } // deleteBinItem
} // BinItemController
