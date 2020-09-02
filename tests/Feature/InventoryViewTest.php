<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Inventory;
use App\Category;
use App\Property;

class InventoryViewTest extends TestCase
{

    use DatabaseMigrations;
    use WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_filter_by_shoes()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response = $this->post('/category', ['category' => "Shoes"]);
        $response = $this->post('/category', ['category' => "Belts"]);
        $response = $this->post('/property', ['property' => "price", 'type' => "numeric"]);
        $response->assertStatus(200);

        $response = $this->post('/property', ['property' => "color", 'type' => "text"]);
        $response->assertStatus(200);

        $response = $this->post('/inventory', [
            "inventory" => "Running Shoes",
            "category_id" => 1,
            "all_properties" => [1 => "54", 3  => "32", 4  => "Yellow, Green", 5 => "small, medium, large", 6 => "Athletic"],
        ]);
        $response = $this->post('/inventory', [
            "inventory" => "Dress Shoes",
            "category_id" => 1,
            "all_properties" => [1 => "80", 3  => "32", 4  => "Yellow, Green", 5 => "small, medium, large", 6 => "Athletic"],
        ]);
        $response = $this->post('/inventory', [
            "inventory" => "Dress Belt",
            "category_id" => 2,
            "all_properties" => [1 => "80", 3  => "32", 4  => "Yellow, Green", 5 => "small, medium, large", 6 => "Athletic"],
        ]);
        $response->assertStatus(200);
        $response = $this->get('/viewInventoryWithId/1');
        $response->assertStatus(200);
        $this->assertEquals(2, count($response->json()['inventory']));
    }

    public function test_sum_shoes()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response = $this->post('/category', ['category' => "Shoes"]);
        $response = $this->post('/category', ['category' => "Belts"]);
        $response = $this->post('/property', ['property' => "price", 'type' => "numeric"]);
        $response->assertStatus(200);

        $response = $this->post('/property', ['property' => "color", 'type' => "text"]);
        $response->assertStatus(200);

        $response = $this->post('/inventory', [
            "inventory" => "Running Shoes",
            "category_id" => 1,
            "all_properties" => [1 => "54", 3  => "32", 4  => "Yellow, Green", 5 => "small, medium, large", 6 => "Athletic"],
        ]);
        $response = $this->post('/inventory', [
            "inventory" => "Dress Shoes",
            "category_id" => 1,
            "all_properties" => [1 => "80", 3  => "32", 4  => "Yellow, Green", 5 => "small, medium, large", 6 => "Athletic"],
        ]);
        $response = $this->post('/inventory', [
            "inventory" => "Dress Belt",
            "category_id" => 2,
            "all_properties" => [1 => "80", 3  => "32", 4  => "Yellow, Green", 5 => "small, medium, large", 6 => "Athletic"],
        ]);
        $response->assertStatus(200);
        $response = $this->get('/sumOnProperty/1/1');
        $response->assertStatus(200);
        $this->assertEquals(134, $response->json());
    }
}
