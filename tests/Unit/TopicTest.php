<?php

namespace Tests\Unit;

use Illuminate\Http\Response;
use Tests\TestCase;

class TopicTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexReturnsDataInValidFormat() {
    
        $this->json('get', 'api/topics')
             ->assertStatus(Response::HTTP_OK);
      }
    
}
