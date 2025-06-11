<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    /**
     * The name of the corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(['Laki-laki', 'Perempuan']);
        $age = fake()->numberBetween(1, 90);
        $dob = now()->subYears($age)->subDays(rand(0, 364));

        return [
            'name' => fake()->name($gender == 'Laki-laki' ? 'male' : 'female'),
            'email' => $age >= 17 ? fake()->unique()->safeEmail() : null, // Anak-anak mungkin tidak punya email
            'phone_number' => fake()->phoneNumber(),
            'age' => $age,
            'address' => fake()->address(),
            'date_of_birth' => $dob->format('Y-m-d'),
            'gender' => $gender,
            'user_id' => null, // Default null, akan diisi di seeder jika terhubung dengan user
        ];
    }
}