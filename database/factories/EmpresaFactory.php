<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empresa>
 */
class EmpresaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->company(),
            'ruc' => $this->faker->unique()->numerify('######-#'),
            'razon_social' => $this->faker->company(). 'S.A',
            'direccion' => $this->faker->address,
            'telefono' => $this->faker->phoneNumber()
        ];
    }
}
