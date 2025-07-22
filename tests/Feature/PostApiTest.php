<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */

 use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_create_post()
{
    Role::create(['name' => 'author', 'guard_name' => 'api']);

    $user = User::factory()->create();
    $user->assignRole('author');

    $token = auth('api')->login($user);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/posts', [
        'title' => 'Test Post',
        'content' => 'This is a test post.',
        'category' => 'Technology',
    ]);

    $response->assertStatus(201)
             ->assertJson([
                 'status' => true,
                 'message' => 'Post created successfully',
             ]);

    $this->assertDatabaseHas('posts', [
        'title' => 'Test Post',
    ]);

    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }
}}
