<?php

namespace App\Http\Controllers\api;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use App\Model\User;
use App\Model\Role;

/**
 * BaseController
 *
 * All controllers inherit from BaseController
 * @author gbh
 */
class BaseController extends Controller
{
    /**
     * Success response method.
     *
     * Returns successful api call json in our desired format, with optional hateoas
     *
     * @param   Int   $status  The HTTP status code of the response, ie 200
     * @param   Mixed $data    The data object to return under 'data'
     * @param   Array $hateoas An array of HATEOAS data, optional
     * @return  JsonResponse
     */
    public function sendResponse(Int $status, $data, Array $hateoas = []):JsonResponse
    {
        $response = [
            'data' => $data,
        ];

        if (count($hateoas) > 0) {
            $response['links'] = $hateoas;
        }

        return response()->json($response, $status);
    }


    /**
     * Error response method
     *
     * @param  Int    $status  The HTTP status code of the response, ie 400
     * @param  Mixed  $error   The string error message to show the user. String or array
     * @param  Mixed  $details Any details the fe may need
     * @return JsonResponse
     */
    public function sendError(Int $status, Mixed $error, Mixed $details=null):JsonResponse
    {
        $response = [
            'error' => $error,
            'details' => $details,
        ];

        return response()->json($response, $status);
    }


    /**
     * Get user id of logged-in user.
     *
     * @return Int user_id
     */
    protected function getLoggedInUserId():Int
    {
        return auth()->guard('api')->user()->id;
    }


    /**
     * Makes a nice hateoas array from LengthAwarePaginator collection
     *
     * @param LengthAwarePaginator $paginationCollection The return collection from Model::paginate()
     * @return Array
     */
    protected function createPaginationFromOrm(LengthAwarePaginator $paginationCollection):Array
    {
        $hateoas = [];
        if ($paginationCollection->hasMorePages()) {
            $hateoas['next_page'] = $paginationCollection->nextPageUrl().
                                    "&size=".
                                    $paginationCollection->count();
        }
        if ($paginationCollection->previousPageUrl()) {
            $hateoas['previous_page'] = $paginationCollection->previousPageUrl().
                                    "&size=".
                                    $paginationCollection->count();
        }
        $hateoas['has_more'] = $paginationCollection->hasMorePages();
        $hateoas['current_page'] = $paginationCollection->currentPage();
        $hateoas['last_page'] = $paginationCollection->lastPage();
        $hateoas['current_size'] = $paginationCollection->count();

        return $hateoas;
    }


    /**
    * Validate pagination query string in a request.
    * This will return the object [ 'page' => A, 'size' => B ] on a valid request,
    * or an error response on failure.
    *
    * @param  Request  $request  The request to the controller method
    * @return Mixed
    */
    protected function validateAndExtractPaginationData(Request $request)
    {
        $page = $request->query('page') !== null ? (int)$request->query('page') : config('app.pagination.default_page_number');
        $size = $request->query('size') !== null ? (int)$request->query('size') : config('app.pagination.default_page_size');

        if ($page < 1) {
            return $this->sendError(400, 'Page number must be at least 1');
        }

        if ($size < 1) {
            return $this->sendError(400, 'Page size must be at least 1');
        }

        $request->page = $page;

        return [
            'page' => $page,
            'size' => $size
        ];
    }
} // BaseController
