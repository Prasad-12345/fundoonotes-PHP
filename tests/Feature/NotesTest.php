<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCaseToCreateNotes()
    {
        $response = $this->json('POST', '/api/addToNotes', [
            'Title' => "Bridgelabz",
            "Description" => "It is a institute and provides training to Engineers"
        ]);

        $response->assertStatus(200);
    }

    public function testCaseToUpdateNotes()
    {
        $response = $this->json('POST', '/api/updateNotes', [
            "userId" => '4',
            'Title' => "Bridgelabz Institute",
            "Description" => "It is a institute and provides training to Engineers"
        ]);

        $response->assertStatus(200);
    }

    public function testCaseToDeleteNotes()
    {
        $response = $this->json('POST', '/api/delete', [
            "userId" => "4",
        ]);

        $response->assertStatus(200);
    }
}
