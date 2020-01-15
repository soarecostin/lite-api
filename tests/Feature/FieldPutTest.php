<?php

namespace Tests\Feature;

use App\Enums\FieldType;
use App\Field;
use App\Http\Resources\FieldResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ApiTestCase;

class FieldPutTest extends ApiTestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider validationDataProvider
     */
    public function testValidation(array $invalidData, string $invalidParameter)
    {
        $field = factory(Field::class)->create([
            'user_id' => $this->user->id,
            'title' => 'Surname',
            'key' => 'surname',
            'type' => FieldType::TEXT(),
        ]);

        $response = $this->actingAs($this->user)
                        ->putJson('/api/fields/'.$field->id, $invalidData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([$invalidParameter]);
    }

    public function validationDataProvider()
    {
        return [
            [['title' => null], 'title'],
            [['title' => ''], 'title'],
            [['title' => []], 'title'],
        ];
    }

    public function testSuccessfulPut()
    {
        $field = factory(Field::class)->create([
            'user_id' => $this->user->id,
            'title' => 'Surname',
            'key' => 'surname',
            'type' => FieldType::TEXT(),
        ]);

        $response = $this->actingAs($this->user)
            ->putJson('/api/fields/'.$field->id, [
                'title' => 'New field title',
                'key' => 'something-else', // verify that it can't change key on update
                'type' => FieldType::BOOLEAN(), // verify that it can't change type on update
            ]);

        $field = Field::find($response->json('data.id'));
        $response->assertStatus(200);
        $response->assertResource(new FieldResource($field));

        $this->assertEquals('New field title', $field->title);
        $this->assertEquals('surname', $field->key);
        $this->assertEquals(FieldType::TEXT(), $field->type);
    }
}
