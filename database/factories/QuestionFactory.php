<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Language;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $language = Language::inRandomOrder()->first(); // Seleciona uma linguagem aleatória
        $category = Category::inRandomOrder()->first(); // Seleciona uma categoria aleatória

        return [
            'id' => Str::uuid(),
            'question' => $this->faker->sentence,
            'answer' => $this->faker->paragraph,
            'language_id' => $language->id,
            'category_id' => $category->id,
        ];
    }
}
