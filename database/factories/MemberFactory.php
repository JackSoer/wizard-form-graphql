<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'birthdate' => $this->faker->date(),
            'report_subject' => $this->faker->paragraph(1),
            'country' => $this->faker->country(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'company' => $this->faker->company(),
            'position' => $this->faker->paragraph(1),
            'about_me' => $this->faker->paragraph(5),
            'photo' => $this->faker->url(),
            'is_visible' => $this->faker->boolean(),
        ];
    }
}
