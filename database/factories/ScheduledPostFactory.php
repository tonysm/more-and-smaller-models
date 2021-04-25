<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\Post;
use App\Models\ScheduledPost;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduledPostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScheduledPost::class;

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
            'publish_at' => $this->faker->dateTime(),
        ];
    }
}
