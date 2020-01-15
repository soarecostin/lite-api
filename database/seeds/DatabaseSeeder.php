<?php

use App\Enums\FieldType;
use App\Field;
use App\Subscriber;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(User::class)->create([
            'email' => 'soarecostin@gmail.com',
            'password' => Hash::make('password')
        ]);

        factory(Field::class)->create([
            'user_id' => $user->id,
            'title' => 'Last name',
            'key' => 'last_name',
            'type' => FieldType::TEXT()
        ]);
        factory(Field::class)->create([
            'user_id' => $user->id,
            'title' => 'Company',
            'key' => 'company',
            'type' => FieldType::TEXT()
        ]);
        factory(Field::class)->create([
            'user_id' => $user->id,
            'title' => 'Country',
            'key' => 'country',
            'type' => FieldType::TEXT()
        ]);
        factory(Field::class)->create([
            'user_id' => $user->id,
            'title' => 'Age',
            'key' => 'age',
            'type' => FieldType::NUMBER()
        ]);
        factory(Field::class)->create([
            'user_id' => $user->id,
            'title' => 'Date of birth',
            'key' => 'date_of_birth',
            'type' => FieldType::DATE()
        ]);
        factory(Field::class)->create([
            'user_id' => $user->id,
            'title' => 'Daily newsletter',
            'key' => 'daily_newsletter',
            'type' => FieldType::BOOLEAN()
        ]);

        factory(Subscriber::class, 50)->states('with_fields')->create([
            'user_id' => $user->id,
        ]);
    }
}
