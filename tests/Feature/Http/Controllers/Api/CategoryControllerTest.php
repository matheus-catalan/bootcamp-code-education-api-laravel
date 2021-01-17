<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex(){
        $category = factory(Category::class)->create();
        $response = $this->get(route('api.categories.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$category->toArray()]);
    }

    public function testShow() {
        $category = factory(Category::class)->create();
        $response = $this->get(route('api.categories.show', ['category' => $category->id]));

        $response
            ->assertStatus(200)
            ->assertJson($category->toArray());   
    }

    // public function testInvalidationData() {
    //     $response = $this->json('POST', route('api.categories.store'), []);
    //     $this->assertInvalidationRequired($response);

    //     $response = $this->json('POST', route('api.categories.store'), [
    //         'name' => str_repeat('a', 256),
    //         'is_active' => 'a'
    //     ]);
    //     $this->assertInvalidationMax($response);
    //     $this->assertInvalidationBoolean($response);


    //     $category = factory(Category::class)->create();
    //     $response = $this->json('PUT', route('api.categories.update', ['category', $category->id]), []);
    //     $this->assertInvalidationRequired($response);
              
    //     $response = $this->json('PUT', route('api.categories.update', ['category', $category->id]) , [
    //         'name' => str_repeat('a', 256),
    //         'is_active' => 'a'
    //     ]);
    //     $this->assertInvalidationMax($response);
    //     $this->assertInvalidationBoolean($response);

        
    // }

    // protected function assertInvalidationRequired(TestResponse $response){
    protected function assertInvalidationRequired($response){    
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonMissingValidationErrors(['is_active'])
            ->assertJsonFragment([
                \Lang::get('validation.required', ['attribute' => 'name'])
            ]);
    }

    // protected function assertInvalidationMax(TestResponse $response){
    protected function assertInvalidationMax($response){    
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name','is_active'])
            ->assertJsonFragment([
                \Lang::get('validation.max.string', ['attribute' => 'name', 'max' => 255])
            ]);
    }

    // protected function assertInvalidationBoolean(TestResponse $response){
    protected function assertInvalidationBoolean($response){    
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name','is_active'])
            ->assertJsonFragment([
                \Lang::get('validation.boolean', ['attribute' => 'is active',])
            ]);
    }

    public function testStore(){
        $response = $this->json('POST', route('api.categories.store'), [
            'name' => 'test',

        ]);
        
        $id = $response->json('id');
        $category = Category::find($id);

        $response
            ->assertStatus(201)
            ->assertJson($category->toArray());
        $this->assertTrue($response->json('is_active'));
        $this->assertNull($response->json('description'));

        $response = $this->json('POST', route('api.categories.store'), [
            'name' => 'test',
            'is_active' => false,
            'description' => 'description'

        ]);
        
        $response->assertJsonFragment([
            'is_active' => false,
            'description' => 'description'
        ]);

    }

    public function testUpdate(){
        $category = factory(Category::class)->create([
            'description' => 'description',
            'is_active' => false
        ]);

        $response = $this->json('PUT', route('api.categories.update', ['category' => $category->id]), [
            'name' => 'test',
            'description' => 'test',
            'is_active' => true
        ]);
        
        $id = $response->json('id');
        $category = Category::find($id);

        $response
            ->assertStatus(200)
            ->assertJson($category->toArray())
            ->assertJsonFragment([
                'description' => 'test',
                'is_active' => true
            ]);

        $response = $this->json('PUT', route('api.categories.update', ['category' => $category->id]), [
                'name' => 'test',
                'description' => '',
            ]);

        $response
            ->assertJsonFragment([
                'description' => null,
            ]);

        $category->description = 'test';
        $category->save();

        $response = $this->json('PUT', route('api.categories.update', ['category' => $category->id]), [
            'name' => 'test',
            'description' => null,
        ]);

        $response
            ->assertJsonFragment([
                'description' => null,
            ]);
 
    }
}