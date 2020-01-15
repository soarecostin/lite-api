<?php

namespace Tests\Feature;

use App\Enums\SubscriberState;
use App\Http\Resources\SubscriberResource;
use App\Subscriber;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ApiTestCase;

class SubscriberPutTest extends ApiTestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider validationDataProvider
     */
    public function testValidation(array $invalidData, string $invalidParameter)
    {
        $this->createTestFields();
        $subscriber = $this->createSubscriber();

        $response = $this->actingAs($this->user)
                        ->putJson('/api/subscribers/'.$subscriber->id, $invalidData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([$invalidParameter]);
    }

    public function validationDataProvider()
    {
        return [
            [['name' => []], 'name'],
            [['name' => 1], 'name'],

            [['state' => 'abc'], 'state'],
            [['state' => ''], 'state'],
            [['state' => []], 'state'],
            [['state' => 10], 'state'],
            [['state' => SubscriberState::Bounced()->key], 'state'],

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

    public function testCannotUpdateVariousFields()
    {
        $this->createTestFields();
        $subscriber = $this->createSubscriber();

        $response = $this->actingAs($this->user)
            ->putJson('/api/subscribers/'.$subscriber->id, [
                'user_id' => factory(User::class)->create(), // verify user cannot be changed
                'email' => 'johndoe@mailerlite.com', // verify email cannot be changed
            ]);

        $updatedSubscriber = Subscriber::find($response->json('data.id'));
        $response->assertStatus(200);
        $response->assertResource(new SubscriberResource($subscriber));

        $this->assertEquals($subscriber->user_id, $updatedSubscriber->user_id);
        $this->assertEquals($subscriber->email, $updatedSubscriber->email);
        $this->assertEquals($subscriber->state, $updatedSubscriber->state);
    }

    public function testSuccessfulPut()
    {
        $this->createTestFields();
        $subscriber = $this->createSubscriber();

        $response = $this->actingAs($this->user)
            ->putJson('/api/subscribers/'.$subscriber->id, [
                'name' => 'John',
                'state' => SubscriberState::Active()->key,
                'fields' => [
                    'surname' => 'Brad',
                    'date_of_birth' => '1987-07-06',
                    'age' => 42,
                    'terms' => false,
                ],
            ]);

        $updatedSubscriber = Subscriber::find($response->json('data.id'));
        $response->assertStatus(200);
        $response->assertResource(new SubscriberResource($updatedSubscriber));

        $this->assertEquals('John', $updatedSubscriber->name);
        $this->assertEquals(SubscriberState::Active(), $updatedSubscriber->state);
        $this->assertEquals('Brad', $updatedSubscriber->fields[0]->pivot->value);
        $this->assertEquals('1987-07-06', $updatedSubscriber->fields[1]->pivot->value);
        $this->assertEquals(42, $updatedSubscriber->fields[2]->pivot->value);
        $this->assertEquals(false, $updatedSubscriber->fields[3]->pivot->value);
    }

    protected function createSubscriber()
    {
        return factory(Subscriber::class)->states('with_fields')->create([
            'user_id' => $this->user->id,
            'email' => 'johndoe@mailerlite.com',
            'name' => 'John',
            'state' => SubscriberState::Unconfirmed(),
        ]);
    }
}
