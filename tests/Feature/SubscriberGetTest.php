<?php

namespace Tests\Feature;

use App\Field;
use App\Http\Resources\SubscriberResource;
use App\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ApiTestCase;

class SubscriberGetTest extends ApiTestCase
{
    use RefreshDatabase;

    public function testGetAllIsValidEndpoint()
    {
        $response = $this->actingAs($this->user)
                        ->getJson('/api/subscribers');
        $response->assertStatus(200);
    }

    public function testItGetsAllSubscribers()
    {
        factory(Field::class, 10)->create([
            'user_id' => $this->user->id,
        ]);

        $subscribers = factory(Subscriber::class, 5)->states('with_fields')->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)
                        ->getJson('/api/subscribers');
        $response->assertResource(SubscriberResource::collection($subscribers));
    }

    public function testItGetsSpecificSubscriberById()
    {
        factory(Field::class, 10)->create([
            'user_id' => $this->user->id,
        ]);

        $subscriber = factory(Subscriber::class)->states('with_fields')->create([
            'user_id' => $this->user->id,
            'name' => 'Costin Soare',
            'email' => 'demo@example.com',
        ]);

        $response = $this->actingAs($this->user)
                        ->getJson('/api/subscribers/'.$subscriber->id);

        $response->assertStatus(200);

        $response->assertResource(new SubscriberResource($subscriber));

        $dbSubscriber = Subscriber::find($response->json('data.id'));
        $this->assertEquals('Costin Soare', $dbSubscriber->name);
        $this->assertEquals('demo@example.com', $dbSubscriber->email);
    }
}
