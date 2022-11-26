<?php
namespace App\Http\Controllers\api\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\api\BaseController;

use App\Libs\Utilities;
use App\Libs\Validation;

use App\Models\User;

/**
 * Admin endpoints
 *
 * Routes to here should be protected by the 'admin_only' middleware.
 */
class AdminController extends BaseController
{
    /**
     * Utilities object
     * @see \App\Libs\Utilities
     */
    protected $utilities;

    /**
     * Validation object
     * @see \App\Libs\Validation
     */
    protected $validation;

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
     * Get all users
     *
     * @return JsonResponse
     * @see http://swagger-bintracker.cloverhitch.ca/#get-/api/admin/users
     */
    public function getUsers(Request $request):JsonResponse
    {
        $users = User::all();
        return $this->sendResponse(200, $users);
    }


    /**
     * Post a new user
     *
     * @return JsonResponse
     * @see 
     */
    public function postUsers(Request $request):JsonResponse
    {
        /**
         * Validate request body
         * HTTP 400
         */
        try {
            $this->validation->validateNewUserRequest($request);
        }
        catch(\Exception $e) {
            return $this->sendError(400, "Validation failures", json_decode($e->getMessage(), true));
        }

        /**
         * Create new user
         */
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->role_id = 2;
            $user->save();
        }
        /**
         * Catch duplicate email
         * HTTP 400
         */
        catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return $this->sendError(400, "User with email address ".$request->email." already exists");
            }
            return $this->sendError(500, $e->getMessage());
        }

        /**
         * HTTP 201
         */
        return $this->sendResponse(201, $user);
    } // postUser
}