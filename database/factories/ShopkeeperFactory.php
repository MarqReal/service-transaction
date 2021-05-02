<?php

namespace Database\Factories;

use App\Models\Shopkeeper;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShopkeeperFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shopkeeper::class;

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
            'cnpj' => $this->faker->cnpj(false),
        ];
    }
}
