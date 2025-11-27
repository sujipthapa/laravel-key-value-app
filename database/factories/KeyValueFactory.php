<?php
namespace Database\Factories;

use App\Models\KeyValue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KeyValue>
 */
class KeyValueFactory extends Factory
{
    protected $model = KeyValue::class;

    public function definition()
    {
        return [
            'key'   => $this->faker->word(),
            'value' => $this->faker->sentence(),
        ];
    }
}
