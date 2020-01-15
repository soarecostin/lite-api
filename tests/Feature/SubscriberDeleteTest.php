<?php

namespace Tests\Feature;

use App\Field;
use App\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ApiTestCase;

class SubscriberDeleteTest extends ApiTestCase
{
    use RefreshDatabase;
    
    public function testDeleteField()
    {
        $this->withoutExceptionHandling();

        factory(Field::class, 10)->create([
            'user_id' => $this->user->id
        ]);

        $subscriber = factory(Subscriber::class)->states('with_fields')->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)
                        ->deleteJson('/api/subscribers/' . $subscriber->id);
        $response->assertStatus(200);
        $response->assertJson([]);
        $this->assertDatabaseMissing('subscribers', [
            'id' => $subscriber->id
        ]);
        $this->assertDatabaseMissing('subscriber_field', [
            'subscriber_id' => $subscriber->id
        ]);
    }
}
