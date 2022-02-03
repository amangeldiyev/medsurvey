<?php

namespace Database\Factories;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class OptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->text(),
            'type' => $this->faker->randomElement([
                Option::TYPE_TEXT,
                Option::TYPE_SELECT
            ]),
            'order' => $this->faker->numberBetween(0, 10),
            'question_id' => Question::factory() 
        ];
    }
}
