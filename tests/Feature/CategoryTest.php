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

    // test
    public function test_create_same_category_twice()
    {
        $response = $this->post('/category', ['category' => "Shoes"]);
        $response->assertStatus(200);

        $response = $this->post('/category', ['category' => "Shoes"]);
        $response->assertStatus(300);
    }

    // test
    public function test_create_two_categories()
    {
        $response = $this->post('/category', ['category' => "Shoes"]);
        $response->assertStatus(200);

        $response = $this->post('/category', ['category' => "Ties"]);
        $response->assertStatus(200);

        $category = Category::count();
        $this->assertEquals($category, 2);
    }

    // test
    public function test_update_category_no_id()
    {
        $response = $this->post('/category/update', ['id' => "", 'selected' => "Shoes"]);
        $response->assertStatus(302);
    }

    // test
    public function test_update_category_no_category_selected()
    {
        $response = $this->post('/category/update', ['id' => "1", 'selected' => ""]);
        $response->assertStatus(302);
    }

    // test
    public function test_update_category()
    {

        $response = $this->post('/category', ['category' => "Shoes"]);
        $response->assertStatus(200);

        $response = $this->post('/category/update', ['id' => "1", 'selected' => "Sandals"]);
        $response->assertStatus(200);

        $category = Category::find(1);
        $this->assertEquals($category->category, "Sandals");
    }

    // test
    public function test_delete_category()
    {

        $response = $this->post('/category', ['category' => "Shoes"]);
        $response->assertStatus(200);

        $response = $this->post('/category/delete', ['id' => "1", 'selected' => "Sandals"]);
        $response->assertStatus(200);

        $category = Category::find(1);
        $this->assertEquals($category->category, "none");
    }
}
