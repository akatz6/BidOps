<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Property;

class PropertyTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;
    
    // test
    public function test_create_property()
    {
        $response = $this->post('/property', ['property' => "price", 'type' => "numeric"]);
        $response->assertStatus(200);
    }

    // test
    public function test_create_property_missing_property()
    {
        $response = $this->post('/property', ['property' => "", 'type' => "numeric"]);
        $response->assertStatus(302);
    }

    // test
    public function test_create_property_missing_type()
    {
        $response = $this->post('/property', ['property' => "price", 'type' => ""]);
        $response->assertStatus(302);
    }

     // test
     public function test_create_property_twice_error()
     {
        $response = $this->post('/property', ['property' => "price", 'type' => "numeric"]);
        $response->assertStatus(200);

        $response = $this->post('/property', ['property' => "price", 'type' => "numeric"]);
        $response->assertStatus(300);
     }

      // test
      public function test_create_property_create_two()
      {
         $response = $this->post('/property', ['property' => "price", 'type' => "numeric"]);
         $response->assertStatus(200);
 
         $response = $this->post('/property', ['property' => "color", 'type' => "text"]);
         $response->assertStatus(200);

         $property = Property::count();
         $this->assertEquals($property, 2);
      }
}
