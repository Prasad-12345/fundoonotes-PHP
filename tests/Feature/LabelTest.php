<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LabelTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCaseToAddLabel()
    {
        $response = $this->json('POST', '/api/addToLabel', [
            'label' => 'Bridgelabz',
            'title_id' => 4
        ]);

        $response->assertStatus(200);        
    }

    public function testCaseToUpdateLabel()
    {
        $response = $this->json('POST', '/api/updateLabel', [
            "userId" => '5',
            'label' => 'Bridgelabz',
            'title_id' => '4'
        ]);

        $response->assertStatus(200);        
    }
}
