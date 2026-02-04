<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookItem>
 */
class BookItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Code defined later usually based on book, but here random
            'code' => strtoupper(Str::random(10)), 
            'status' => 'available',
        ];
    }
}
