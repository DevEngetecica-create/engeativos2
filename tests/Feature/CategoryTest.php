<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\User;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_creation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/categories', [
            'name' => 'Test Category',
            'color' => '#ffffff',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('categories', ['name' => 'Test Category']);
    }

    // Adicione mais testes conforme necessário...
}
