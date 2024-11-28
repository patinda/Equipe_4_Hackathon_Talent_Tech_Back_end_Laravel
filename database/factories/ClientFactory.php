<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'last_name' => $this->faker->lastName(), // Translated 'nom' to 'last_name'
            'first_name' => $this->faker->firstName(), // Translated 'prenoms' to 'first_name'
            'phone_number' => $this->faker->unique()->phoneNumber(), // Added 'phone_number'
            'cnib_number' => $this->faker->unique()->numerify('##########'), // Translated 'numero_cnib' to 'cnib_number'
            'issue_date' => $this->faker->date(), // Translated 'date_delivrance' to 'issue_date'
            'expiry_date' => $this->faker->date(), // Translated 'date_expiration' to 'expiry_date'
            'secondary_phone' => $this->faker->phoneNumber(), // Translated 'telephone_secondaire' to 'secondary_phone'
            'birth_date' => $this->faker->date(), // Translated 'date_naissance' to 'birth_date'
            'birth_place' => $this->faker->city(), // Translated 'lieu_naissance' to 'birth_place'
            'orange_money_password' => Hash::make('password123'),
            'total_amount' => $this->faker->randomFloat(2, 1000, 50000),
            'issue_place' => $this->faker->city(), // Translated 'lieu_delivrance' to 'issue_place'
            'front_cnib_photo' => $this->faker->imageUrl(640, 480, 'front_cnib'), // Added front CNIB photo
            'back_cnib_photo' => $this->faker->imageUrl(640, 480, 'back_cnib'), // Added back CNIB photo
            'selfie_with_cnib' => $this->faker->imageUrl(640, 480, 'selfie_cnib'), // Added selfie with CNIB
        ];
    }
}
