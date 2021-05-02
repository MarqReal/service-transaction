<?php

namespace Database\Factories;

use App\Models\PhysicalPerson;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhysicalPersonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PhysicalPerson::class;

    protected function withFaker()
    {
        return \Faker\Factory::create('pt_BR');
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cpf' => $this->faker->cpf(false),
        ];
    }
}
