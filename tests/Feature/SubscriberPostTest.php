<?php

namespace Tests\Feature;

use App\Enums\FieldType;
use App\Enums\SubscriberState;
use App\Field;
use App\Http\Resources\SubscriberResource;
use App\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ApiTestCase;

class SubscriberPostTest extends ApiTestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider validationDataProvider
     */
    public function testPostValidation(array $invalidData, string $invalidParameter)
    {
        $this->createTestFields();

        $data = array_merge($this->getValidSubscriberData(), $invalidData);

        $response = $this->actingAs($this->user)
                        ->postJson('/api/subscribers', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([$invalidParameter]);
    }

    public function validationDataProvider()
    {
        return [
            [['email' => null], 'email'],
            [['email' => ''], 'email'],
            [['email' => 'abc'], 'email'],
            [['email' => 673890], 'email'],
            [['email' => []], 'email'],
            [['email' => 'abc@a.d'], 'email'],
            [['email' => 'abc@invalid-domain-name-123456789.com'], 'email'],

            [['name' => []], 'name'],
            [['name' => 1], 'name'],
            
            [['state' => 'abc'], 'state'],
            [['state' => ''], 'state'],
            [['state' => []], 'state'],
            [['state' => 10], 'state'],

            [['fields' => ['surname'=>123]], 'fields.surname'],
            
            [['fields' => ['date_of_birth'=>123]], 'fields.date_of_birth'],
            [['fields' => ['date_of_birth'=>'abc']], 'fields.date_of_birth'],
            [['fields' => ['date_of_birth'=>'07-1098-06']], 'fields.date_of_birth'],
            
            [['fields' => ['age'=>'abc']], 'fields.age'],
            [['fields' => ['age'=>'1980-07-06']], 'fields.age'],
            [['fields' => ['age'=> []]], 'fields.age'],

            [['fields' => ['terms'=> 123]], 'fields.terms'],
            [['fields' => ['terms'=> 'abc']], 'fields.terms'],
        ];
    }

    public function testSubscriberEmailUnique()
    {
        $this->createTestFields();
        
        factory(Subscriber::class)->states('with_fields')->create([
            'user_id' => $this->user->id,
            'email' => 'johndoe@mailerlite.com',
            'name' => 'John',
            'state' => SubscriberState::Unconfirmed()->value,
        ]);

        $response = $this->actingAs($this->user)
            ->postJson('/api/subscribers', [
                'email' => 'johndoe@mailerlite.com',
                'name' => 'John',
                'state' => SubscriberState::Active()->key,
            ]);
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function testSuccessfulPost()
    {
        $this->createTestFields();

        $subscriberData = $this->getValidSubscriberData();
        $response = $this->actingAs($this->user)
                        ->postJson('/api/subscribers', $subscriberData);

        $subscriber = Subscriber::with('fields')->find($response->json('data.id'));
        $response->assertStatus(201);
        $response->assertResource(new SubscriberResource($subscriber));
        
        $this->assertEquals($subscriberData['email'], $subscriber->email);
        $this->assertEquals($subscriberData['name'], $subscriber->name);
        $this->assertEquals($subscriberData['state'], $subscriber->state->key);
        $this->assertEquals(count($subscriberData['fields']), $subscriber->fields->count());
        
        collect($subscriberData['fields'])->each(function($value, $key) use ($subscriber) {
            $field = Field::where('key', $key)->first();
            $this->assertContains($field->id, $subscriber->fields->pluck('id')->all());
        });

        $this->assertEquals('Doe', $subscriber->fields[0]->pivot->value);
        $this->assertEquals('1980-07-06', $subscriber->fields[1]->pivot->value);
        $this->assertEquals('39', $subscriber->fields[2]->pivot->value);
        $this->assertEquals(true, $subscriber->fields[3]->pivot->value);
    }

    protected function createTestFields()
    {
        factory(Field::class)->create([
            'user_id' => $this->user->id,
            'title' => 'Surname',
            'key' => 'surname',
            'type' => FieldType::TEXT()
        ]);

        factory(Field::class)->create([
            'user_id' => $this->user->id,
            'title' => 'Date of birth',
            'key' => 'date_of_birth',
            'type' => FieldType::DATE()
        ]);

        factory(Field::class)->create([
            'user_id' => $this->user->id,
            'title' => 'Age',
            'key' => 'age',
            'type' => FieldType::NUMBER()
        ]);

        factory(Field::class)->create([
            'user_id' => $this->user->id,
            'title' => 'Terms',
            'key' => 'terms',
            'type' => FieldType::BOOLEAN()
        ]);
    }

    protected function getValidSubscriberData()
    {
        return [
            'user_id' => $this->user->id,
            'email' => 'johndoe@mailerlite.com',
            'name' => 'John',
            'state' => SubscriberState::Unconfirmed()->key,
            'fields' => [
              'surname' => 'Doe',
              'date_of_birth' => '1980-07-06',
              'age' => 39,
              'terms' => true
            ]
        ];
    }
}