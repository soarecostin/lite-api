<?php

namespace Tests\Unit;

use App\Enums\SubscriberState;
use App\Subscriber;
use PHPUnit\Framework\TestCase;

class SubscriberTest extends TestCase
{
    public function testSubscriberState()
    {
        $subscriber = new Subscriber();
        $subscriber->state = SubscriberState::Unconfirmed();

        $this->assertInstanceOf(SubscriberState::class, $subscriber->state);
        $this->assertEquals(SubscriberState::Unconfirmed(), $subscriber->state);
    }
}
