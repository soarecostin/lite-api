<?php

namespace Tests\Feature;

use App\Enums\FieldType;
use App\Field;
use App\Http\Resources\FieldResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ApiTestCase;

class FieldGetTest extends ApiTestCase
{
    use RefreshDatabase;

    public function testGetAllIsValidEndpoint()
    {
        $response = $this->actingAs($this->user)
                        ->getJson('/api/fields');
        $response->assertStatus(200);
    }

    public function testGetAllFields()
    {
        $fields = factory(Field::class, 10)->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)
                        ->getJson('/api/fields');
        $response->assertResource(FieldResource::collection($fields));
    }

    public function testGetField()
    {
        $field = factory(Field::class)->create([
            'user_id' => $this->user->id,
            'title' => 'Surname',
            'key' => 'surname',
            'type' => FieldType::TEXT()
        ]);
        
        $response = $this->actingAs($this->user)
                        ->getJson('/api/fields/' . $field->id);

        $response->assertStatus(200);
        $response->assertResource(new FieldResource($field));
        $this->assertDatabaseHas('fields', [
            'key' => 'surname'
        ]);
        
        $dbField = Field::find($response->json('data.id'));
        $this->assertEquals(FieldType::TEXT(), $dbField->type);
    }
}
