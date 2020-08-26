<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Category;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

   // test
   public function test_create_category()
   {
       $response = $this->post('/category', ['category' => "Shoes"]);
       $response->assertStatus(200);
   }

   // test
   public function test_create_empty_category()
   {
       $response = $this->post('/category', ['category' => ""]);
       $response->assertStatus(302);
   }

   public function test_create_same_category_twice()
   {
       $response = $this->post('/category', ['category' => "Shoes"]);
       $response->assertStatus(200);

       $response = $this->post('/category', ['category' => "Shoes"]);
       $response->assertStatus(300);
   }

   public function test_create_tow_categories()
   {
       $response = $this->post('/category', ['category' => "Shoes"]);
       $response->assertStatus(200);

       $response = $this->post('/category', ['category' => "Ties"]);
       $response->assertStatus(200);

       $category = Category::count();
       $this->assertEquals($category, 2);
   }
}
