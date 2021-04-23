<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Draft;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creates_draft()
    {
        $user = User::factory()->has(Blog::factory())->create();

        $draft = $user->blog->addDraft($post = new Post([
            'title' => 'lorem',
            'content' => 'lorem ipsum',
        ]));

        $this->assertTrue($draft->blog->is($user->blog), 'Blog is not the same as the draft.');
        $this->assertTrue($draft->post->is($post), 'Post is not the same as the draft.');
    }

    /** @test */
    public function publishes_drafts()
    {
        $draft = Draft::factory()->create();

        $publication = $draft->publish();

        $this->assertFalse($draft->exists);
        $this->assertCount(1, $draft->blog->publications);
        $this->assertTrue($draft->blog->publications->first()->is($publication));
        $this->assertTrue($draft->blog->publications->first()->post->is($draft->post));
    }

    /** @test */
    public function schedules_a_draft()
    {
        $this->travelTo(Carbon::now());

        $draft = Draft::factory()->create();

        $scheduled = $draft->schedule(
            $when = Carbon::now()->addDays(5)->milliseconds(0)
        );
        $scheduled->refresh();

        $this->assertFalse($draft->exists);
        $this->assertCount(1, $draft->blog->scheduledPosts);
        $this->assertTrue($draft->blog->scheduledPosts->first()->is($scheduled));
        $this->assertTrue($scheduled->publish_at->eq($when));
        $this->assertTrue($draft->blog->scheduledPosts->first()->post->is($scheduled->post));
    }
}
