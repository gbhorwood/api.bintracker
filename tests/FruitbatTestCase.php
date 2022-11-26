<?php
namespace Tests;

use Carbon\Carbon;
use GuzzleHttp\Client;
use cebe\openapi\Reader;
use Symfony\Component\Yaml\Yaml;
use cebe\openapi\ReferenceContext;
use Illuminate\Testing\TestResponse;
use Mmal\OpenapiValidator\Validator;
use Osteel\OpenApi\Testing\ValidatorBuilder;

abstract class FruitbatTestCase extends TestCase
{
    use CreatesApplication;

    /**
     * Validates the response from the api in $apiResponse against the swagger spec.
     *
     * @param   Illuminate\Testing\TestResponse $apiResponse    The testing response from the api call
     * @param   String                          $httpMethod     The method of the request, ie 'get' or 'post'
     * @param   String                          $endpoint       The endpoint with leading slash, ie '/foo/bar'
     * @return  void
     */
    protected function validateSwagger(\Illuminate\Testing\TestResponse $apiResponse, String $httpMethod, String $endpoint):void
    {
        $httpMethod = strtolower($httpMethod);
        $swaggerJson = file_get_contents(SWAGGER_JSON);
        $validator = ValidatorBuilder::fromJson($swaggerJson)->getValidator();
        $swaggerResult = $validator->validate($apiResponse->baseResponse, $endpoint, $httpMethod);
        $this->assertTrue($swaggerResult);
    }

    /**
     * Get body of response as array
     *
     * @param  Illuminate\Testing\TestResponse $response
     * @return Array
     */
    protected function getBody(TestResponse $response):Array
    {
        $body = json_decode($response->decodeResponseJson()->json);
        return isset($body->data) ? (array)$body->data : (array)$body;
    }
}
