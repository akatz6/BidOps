<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Inventory;

class InventoryTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    // test
    public function test_create_inventory()
    {
        $response = $this->post('/inventory', [
            "inventory" => "Running Shoes",
            "category_id" => 1,
            "all_properties" => [1 => "54", 3  => "32", 4  => "Yellow, Green", 5 => "small, medium, large", 6 => "Athletic"],
        ]);
        $invetory = Inventory::find(1);
        $inventoryCount = Inventory::count();
        $response->assertStatus(200);
        $this->assertEquals($invetory->inventory, "Running Shoes");
        $this->assertEquals($inventoryCount, 1);
    }

    // test
    public function test_create_inventory_missing_inventory()
    {
        $response = $this->post('/inventory', [
            "inventory" => "",
            "category_id" => 1,
            "all_properties" => [1 => "54", 3  => "32", 4  => "Yellow, Green", 5 => "small, medium, large", 6 => "Athletic"],
        ]);
        $response->assertStatus(302);
    }

    // test
    public function test_create_inventory_missing_category()
    {
        $response = $this->post('/inventory', [
            "inventory" => "Running Shoes",
            "category_id" => "",
            "all_properties" => [1 => "54", 3  => "32", 4  => "Yellow, Green", 5 => "small, medium, large", 6 => "Athletic"],
        ]);
        $response->assertStatus(302);
    }

    // test
    public function test_create_inventory_already_exists()
    {
        $response = $this->post('/inventory', [
            "inventory" => "Running Shoes",
            "category_id" => 1,
            "all_properties" => [1 => "54", 3  => "32", 4  => "Yellow, Green", 5 => "small, medium, large", 6 => "Athletic"],
        ]);
        $invetory = Inventory::find(1);
        $inventoryCount = Inventory::count();
        $response->assertStatus(200);
        $this->assertEquals($invetory->inventory, "Running Shoes");
        $this->assertEquals($inventoryCount, 1);

        $response = $this->post('/inventory', [
            "inventory" => "Running Shoes",
            "category_id" => 1,
            "all_properties" => [1 => "54", 3  => "32", 4  => "Yellow, Green", 5 => "small, medium, large", 6 => "Athletic"],
        ]);
        $response->assertStatus(300);
    }

    // test
    public function test_delete_inventory()
    {
        $response = $this->post('/inventory', [
            "inventory" => "Running Shoes",
            "category_id" => 1,
            "all_properties" => [1 => "54", 3  => "32", 4  => "Yellow, Green", 5 => "small, medium, large", 6 => "Athletic"],
        ]);
        $invetory = Inventory::find(1);
        $inventoryCount = Inventory::count();
        $response->assertStatus(200);
        $this->assertEquals($invetory->inventory, "Running Shoes");
        $this->assertEquals($inventoryCount, 1);

        $response = $this->post('/inventory/delete', ["id" => 1]);
        $inventoryCount = Inventory::count();
        $this->assertEquals($inventoryCount, 0);
    }
}
