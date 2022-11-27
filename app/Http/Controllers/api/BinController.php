<?php
namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

use App\Models\Bin;
use App\Models\Item;
use App\Models\BinItem;

use App\Libs\Utilities;
use App\Libs\Validation;

use App\Jobs\CreateQRCode;

/**
 * BinController
 *
 * @author gbh
 */
class BinController extends BaseController
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
     * Return digest of bins for making menus
     *
     * @return JsonResponse
     * @see http://swagger-bintracker.cloverhitch.ca/#get-/api/bins/digest
     */
    public function getBinsDigest(Request $request):JsonResponse
    {
        $bins = Bin::whereMine()->get();
        return $this->sendResponse(200, $bins);
    } // getBinsDigest


    /**
     * Creates a bin for the session user
     *
     * @return JsonResponse
     */
    public function postBins(Request $request):JsonResponse
    {
        /**
         * Validate request body
         * HTTP 400
         */
        try {
            $this->validation->validateBinRequest($request);
        }
        catch(\Exception $e) {
            return $this->sendError(400, "Validation failures", json_decode($e->getMessage(), true));
        }

        /**
         * Create bin
         */
        $bin = new Bin();
        $bin->name = $request->name;
        $bin->user_id = $this->getLoggedInUserId();
        $bin->save();

        /**
         * Create the QR code for this bin
         * Worker call
         * @see App.Jobs.CreateQRCode
         */
        dispatch(new CreateQRCode($bin->id));

        /**
         * Select back and return
         * HTTP 200
         */
        $bin = Bin::find($bin->id);
        return $this->sendResponse(201, $bin);
    }


    /**
     * Return all items in one bin
     *
     * @return JsonResponse
     * @see http://swagger-bintracker.cloverhitch.ca/#get-/api/bins/-id-/items
     */
    public function getBinItems(Int $id, Request $request):JsonResponse
    {
        /**
         * Validate bin exists
         * HTTP 404
         */
        $bin = Bin::whereMine()->find($id);
        if (!$bin) {
            return $this->sendError(404, "Bin does not exist");
        }
        
        /**
         * Get items
         */
        $items = $bin->items;

        /**
         * HTTP 200
         */
        return $this->sendResponse(200, $items);
    } // getBinItems
} // BinController
