<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class AddGivenCoursesSeeder extends Seeder
{
    public function run(): void
    {
        if ($this->isDataAlreadyGiven()) {
            return;
        }

        Course::create([
            'paddle_product_id' => '34779',
            'slug' => 'laravel-for-beginners',
            'title' => 'Laravel For Beginners',
            'tagline' => 'Learn the basics of Laravel',
            'description' => 'Learn the basics of Laravel',
            'image_name' => 'laravel-for-beginners.png',
            'learnings' => [
                'Learn how to install Laravel',
                'Learn how to create a new Laravel project',
            ],
            'released_at' => now()
        ]);

        Course::create([
            'paddle_product_id' => '34780',
            'slug' => 'advanced-laravel',
            'title' => 'Advanced Laravel',
            'tagline' => 'Learn the advanced features of Laravel',
            'description' => 'Learn the advanced features of Laravel',
            'image_name' => 'advanced-laravel.png',
            'learnings' => [
                'How to use the service container',
                'How to use the Facade pattern',
                'How to use the Repository pattern',
            ],
            'released_at' => now()
        ]);

        Course::create([
            'paddle_product_id' => '874362',
            'slug' => 'tdd-the-laravel-way',
            'title' => 'TDD The Laravel Way',
            'tagline' => 'Learn how to test your Laravel application',
            'description' => 'Learn how to test your Laravel application',
            'image_name' => 'tdd-the-laravel-way.png',
            'learnings' => [
                'How to write unit tests',
                'How to write feature tests',
                'How to use the Laravel testing tools'
            ],
            'released_at' => now()
        ]);
    }

    private function isDataAlreadyGiven(): bool
    {
        return Course::where('title', 'Laravel For Beginners')->exists()
                && Course::where('title', 'Advanced Laravel')->exists()
                && Course::where('title', 'TDD The Laravel Way')->exists();
    }
}
