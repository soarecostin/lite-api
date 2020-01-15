<?php

namespace Tests\Feature;

use App\Enums\FieldType;
use App\Field;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ApiTestCase;

class FieldTest extends ApiTestCase
{
    use RefreshDatabase;

    public function testDeleteField()
    {
        $field = factory(Field::class)->create([
            'user_id' => $this->user->id,
            'title' => 'Surname',
            'key' => 'surname',
            'type' => FieldType::TEXT(),
        ]);

        $response = $this->actingAs($this->user)
                        ->deleteJson('/api/fields/'.$field->id);

        $response->assertStatus(200);
        $response->assertJson([]);
        $this->assertDatabaseMissing('fields', [
            'id' => $field->id,
        ]);
    }
}
