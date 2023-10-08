<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function released(Carbon $date = null): self
    {
        return $this->state(
            fn (array $attributes) => [
                'released_at' => $date ?? Carbon::now(),
            ]
        );
    }
}
