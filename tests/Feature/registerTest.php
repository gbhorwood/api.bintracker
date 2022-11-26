<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\FruitbatTestCase;
use App\Models\User;

class registerTest extends FruitbatTestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
        \Artisan::call('passport:install');
        $this->seed();
    }

    /**
     * GET /api/register
     * 201
     *
     * @return void
     */
    public function test_register_201()
    {

        $request = [
            'email' => 'three@example.ca',
            'name' => 'user three',
            'password' => 'onetwothree',
            'c_password' => 'onetwothree',
        ];

        $response = $this->postJson('/api/register', $request);

        $response->assertStatus(201);

        // test valid swagger
        $this->validateSwagger($response, 'POST', '/api/register');
    }

    /**
     * GET /api/register
     * 403
     *
     * @return void
     */
    public function test_register_403()
    {

        $request = [
            'email' => 'one@example.ca', // duplicate email
            'name' => 'user three',
            'password' => 'onetwothree',
            'c_password' => 'onetwothree',
        ];

        $response = $this->postJson('/api/register', $request);

        $response->assertStatus(403);
    }

    /**
     * GET /api/register
     * 400
     *
     * @return void
     * @dataProvider http400Provider
     */
    public function test_register_400($email, $name, $password, $c_password)
    {

        $request = [
            'email' => $email,
            'name' => $name,
            'password' => $password,
            'c_password' => $c_password
        ];

        $response = $this->postJson('/api/register', $request);

        $response->assertStatus(400);
    }

    /**
     * Data provider for 400
     */
    public function http400Provider()
    {
        return [
            [ "notanemail", "some name", "somepassword", "somepassword"],
            [ null, "some name", "somepassword", "somepassword"],
            [ "three@example.ca", null, "somepassword", "somepassword"],
            [ "three@example.ca", "some name", "somepassword", "notmatched"],
        ];
    } // http403Provider
}
