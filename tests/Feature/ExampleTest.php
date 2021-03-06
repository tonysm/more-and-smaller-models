<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Draft;
use App\Models\Post;
use App\Models\Publication;
use App\Models\ScheduledPost;
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

        $draft = $user->blog->addDraft($post = $user->posts()->create([
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

        // To make sure the publish_at casting is there...
        $scheduled->refresh();

        $this->assertFalse($draft->exists);
        $this->assertCount(1, $draft->blog->scheduledPosts);
        $this->assertTrue($draft->blog->scheduledPosts->first()->is($scheduled));
        $this->assertTrue($scheduled->publish_at->eq($when));
        $this->assertTrue($draft->blog->scheduledPosts->first()->post->is($scheduled->post));
    }

    /** @test */
    public function scheduled_post_is_published()
    {
        $scheduled = ScheduledPost::factory()->create();

        $publication = $scheduled->publish();

        $this->assertFalse($scheduled->exists);
        $this->assertCount(1, $scheduled->blog->publications);
        $this->assertTrue($scheduled->blog->publications->first()->is($publication));
        $this->assertTrue($scheduled->blog->publications->first()->post->is($scheduled->post));
    }

    /** @test */
    public function unpublish_post()
    {
        $publication = Publication::factory()->create();

        $draft = $publication->unpublish();

        $this->assertFalse($publication->exists);
        $this->assertCount(1, $publication->blog->drafts);
        $this->assertTrue($publication->blog->drafts->first()->is($draft));
        $this->assertTrue($publication->blog->drafts->first()->post->is($draft->post));
    }
}
