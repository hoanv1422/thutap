<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    use RefreshDatabase;

    public function test_create_product() {
        $product = Product::factory()->create([
            'name' => 'Laptop Gaming',
            'price' => 1500,
            'stock' => 10,
        ]);

        $this->assertDatabaseHas('products', ['name' => 'Laptop Gaming']);
    }

    public function test_product_fillable() {
        $product = new Product();
        $fillable = ['name', 'price', 'stock', 'description', 'image', 'is_active'];
        
        $this->assertEqualsCanonicalizing($fillable, $product->getFillable());
    }
    
}
