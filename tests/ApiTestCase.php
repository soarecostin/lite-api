<?php

namespace Tests;

use App\Enums\FieldType;
use App\Field;
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

    protected function createTestFields()
    {
        factory(Field::class)->create([
            'user_id' => $this->user->id,
            'title' => 'Surname',
            'key' => 'surname',
            'type' => FieldType::TEXT(),
        ]);

        factory(Field::class)->create([
            'user_id' => $this->user->id,
            'title' => 'Date of birth',
            'key' => 'date_of_birth',
            'type' => FieldType::DATE(),
        ]);

        factory(Field::class)->create([
            'user_id' => $this->user->id,
            'title' => 'Age',
            'key' => 'age',
            'type' => FieldType::NUMBER(),
        ]);

        factory(Field::class)->create([
            'user_id' => $this->user->id,
            'title' => 'Terms',
            'key' => 'terms',
            'type' => FieldType::BOOLEAN(),
        ]);
    }
}
