<?php

namespace Tests\Feature;

use App\Enums\FieldType;
use App\Field;
use App\Http\Resources\FieldResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\ApiTestCase;

class FieldPostTest extends ApiTestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider validationDataProvider
     */
    public function testValidation(array $invalidData, string $invalidParameter)
    {
        $data = array_merge($this->getValidFieldData(), $invalidData);

        $response = $this->actingAs($this->user)
                        ->postJson('/api/fields', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([$invalidParameter]);
    }

    public function validationDataProvider()
    {
        return [
            [['title' => null], 'title'],
            [['title' => ''], 'title'],
            [['title' => []], 'title'],

            [['type' => null], 'type'],
            [['type' => ''], 'type'],
            [['type' => []], 'type'],
            [['type' => 10], 'type'],
            [['type' => 'abc'], 'type'],
        ];
    }

    public function testSuccessfulPost()
    {
        $this->withoutExceptionHandling();
        $fieldData = $this->getValidFieldData();

        $response = $this->actingAs($this->user)
                        ->postJson('/api/fields', $fieldData);

        $field = Field::find($response->json('data.id'));
        $response->assertStatus(201);
        $response->assertResource(new FieldResource($field));
        
        $this->assertEquals($fieldData['title'], $field->title);
        $this->assertEquals(Str::snake($fieldData['title']), $field->key);
        $this->assertEquals($fieldData['type'], $field->type->key);
    }
    
    protected function getValidFieldData()
    {
        return [
            'user_id' => $this->user->id,
            'title' => 'Date of birth',
            'type' => FieldType::DATE()->key,
        ];
    }
}
