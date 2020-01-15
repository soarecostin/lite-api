<?php 

namespace Tests;

use App\User;

abstract class ApiTestCase extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        // $this->actingAs($this->user);
    }
}