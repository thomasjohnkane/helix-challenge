<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testItAuthenticates()
    {
        $response = $this->get('/api/products/1');

        $response->assertStatus(500);

        $user = factory(\App\User::class)->create();

        $response = $this->get('/api/products/user?api_token='.$user->api_token);

        $response->assertStatus(200);
    }

    public function test_can_create_product() {
        $user = factory(\App\User::class)->create();

        $product = factory(\App\Product::class)->make();
        $product->user_id = $user->id;

        $this->post('/api/products?api_token='.$user->api_token, $product->toArray())
            ->assertStatus(201);

        $this->assertDatabaseHas('products', $product->toArray());
    }
    public function test_can_update_product() {
        $user = factory(\App\User::class)->create();
        $product = factory(\App\Product::class)->create();
        $product->user_id = $user->id;
        $product->save();
        $faker = \Faker\Factory::create();
        $data = [
            'name' => $faker->name
        ];
        $this->put('/api/products/'.$product->id.'?api_token='.$user->api_token, $data)
            ->assertStatus(200);
        $this->assertDatabaseHas('products', $data);
    }
    public function test_can_show_product()
    {
        $user = factory(\App\User::class)->create();

        $product = factory(\App\Product::class)->create();
        $product->user_id = $user->id;
        $product->save();

        $response = $this->get('/api/products/'.$product->id.'?api_token='.$user->api_token);

        $response->assertStatus(200);
    }
    public function test_can_delete_product() {
        $user = factory(\App\User::class)->create();
        $product = factory(\App\Product::class)->create();
        $product->user_id = $user->id;
        $product->save();
        $this->delete('/api/products/'.$product->id.'?api_token='.$user->api_token)
            ->assertStatus(204);
    }
    public function test_can_list_products() {
        $user = factory(\App\User::class)->create();
        $product = \App\Product::first();

        $this->get('/api/products/?api_token='.$user->api_token)
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => $product->name
            ]);
    }
}
