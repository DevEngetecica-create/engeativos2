<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Subcategory;
use App\Models\User;

class SubcategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_creation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/subcategories', [
            'name' => 'Test Subcategory',
            'color' => '#ffffff',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('subcategories', ['name' => 'Test Subcategory']);
    }

    // Adicione mais testes conforme necessário...
}
