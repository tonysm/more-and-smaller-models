<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\Draft;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class DraftFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Draft::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'blog_id' => Blog::factory(),
            'post_id' => Post::factory(),
        ];
    }
}
