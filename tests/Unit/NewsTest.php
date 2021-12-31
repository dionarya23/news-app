<?php

namespace Tests\Unit;

use Illuminate\Http\Response;
use Tests\TestCase;

class NewsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexReturnsDataInValidFormat() {
    
        $this->json('get', 'api/news')
             ->assertStatus(Response::HTTP_OK);
      }
    
}
