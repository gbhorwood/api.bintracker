<?php
namespace App\Http\Controllers\api;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\api\BaseController as BaseController;

use App\Models\User;

use App\Libs\Utilities;
use App\Libs\Validation;

class RegisterController extends BaseController
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
     * Register
     * Basic register, no email/sms validation.
     *
     * @return JsonResponse
     */
    public function register(Request $request):JsonResponse
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

        // did you run php artisan passport:install
        try {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);

            $success['token'] =  $user->createToken(config('app.name'))->accessToken;
            $success['name'] =  $user->name;
            $success['role_id'] =  2;

            return $this->sendResponse(201, $success);
        }
        // catch duplicate entry
        catch (\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
                return $this->sendError(403, "User with email address '${input['email']}' already exists");
            }
            return $this->sendError(403, $e->getMessage());
        }

    } // register


    /**
     * Login
     *
     * @return JsonResponse
     */
    public function login(Request $request):JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken(config('app.name'))->accessToken;
            $success['name'] =  $user->name;
            $success['email'] =  $user->email;
            $success['user_id'] =  $user->id;
            return $this->sendResponse(201, $success);
        } 
        else{ 
            return $this->sendError(403, "Unauthorized");
        } 
    }

} // RegisterController
