<?php
namespace App\Libs;

use Exception;
use Validator;

use Illuminate\Http\Request;

/**
 * Validtion methods
 *
 */
class Validation {

    /**
     * Validates request body of an item
     *
     * @param  Request $request
     * @return void
     * @throws Exception
     */
    public function validateItemRequest(Request $request):void
    {
        $rules = [
            'name' => 'required|string|max:254',
            'amount' => 'required|integer|min:1',
            'unit_id' => 'required|integer|min:1|exists:units,id',
            'image' => 'string|max:254',
        ];

        $this->doValidation($request, $rules);
    }


    /**
     * Validates request body of a bin
     *
     * @param  Request $request
     * @return void
     * @throws Exception
     */
    public function validateBinRequest(Request $request):void
    {
        $rules = [
            'name' => 'required|string|max:254',
        ];

        $this->doValidation($request, $rules);
    }


    /**
     * Validates request body of a new User
     *
     * @param  Request $request
     * @return void
     * @throws Exception
     */
    public function validateNewUserRequest(Request $request):void
    {
        $rules = [
            'name' => 'required|string|max:254',
            'email' => 'required|email',
            'password' => 'required|string',
            'c_password' => 'required|string|same:password',
        ];

        $this->doValidation($request, $rules);
    }


    /**
     * Validates a json string against a set of Laravel validation rules.
     *
     * @note Only flat json at this point
     *
     * @param  Array    $rules   The Laravel validator ruleset to validate against
     * @param  Request  $request The request object laravel injects into the controller
     * @return Array    An array of the validation errors. Empty array is no errors.
     */
    protected function validateJson(array $rules, Request $request):Array
    {
        $requestContent = $request->getContent();
        $data = json_decode($requestContent, true);
        $data = $data ? $data : [];
        $validator = Validator::make($data, $rules);

        if (!$validator->passes()) {
            $errors = $validator->errors();
            $errorArray = [];
            foreach ($errors->getMessages() as $key => $message) {
                $errorArray[$key] = $message;
            }
            return $errorArray;
        }

        return [];
    }

    /**
     * Runs the validation on the request, throws Exception on any errors
     *
     * The exception message is a json string of all validation failures.
     *
     * @param  Request $request
     * @param  Array   $rules   The validation rules array
     * @return void
     * @throws Exception
     */
    protected function doValidation(Request $request, Array $rules):void
    {
        $validationResult = $this->validateJson($rules, $request);

        if (count($validationResult) > 0) {
            throw new \Exception(json_encode($validationResult));
        }
    }
}