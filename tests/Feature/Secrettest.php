<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Secrettest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Json request.
     *
     * @return void
     */
    public function testOneSecret()
    {
        $this->json('POST', '/secret', ['hash' => '688787d8ff144c502c7f5cffaafe2cc588d86079f9de88304c26b0cb99ce91c6'])
            ->seeJson([
                'remainingViews' => 1,
            ]);
    }
}
