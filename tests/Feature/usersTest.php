<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class userTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_register()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Appilication/json'
        ])->json('POST', '/api/newregister',[
            "name" => "yogesh",
            "email" => "yogesh@gmail.com",
            "password" => "yogesh@123"
        ]);
        $response->assertStatus(200);
    }

    public function test_user_can_login(){
        $response = $this->json('POST', '/api/login',[
            "email" => "vipul@gmail.com",
            "password" => "Vipul@123"
        ]);
        $response->assertStatus(200);
    }
}
