<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Blog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function drafts()
    {
        return $this->hasMany(Draft::class);
    }

    public function publications()
    {
        return $this->hasMany(Publication::class);
    }

    public function scheduledPosts()
    {
        return $this->hasMany(ScheduledPost::class);
    }

    public function addDraft(Post $post): Draft
    {
        $this->user->posts()->save($post);

        return $this->drafts()->create([
            'post_id' => $post->id,
        ]);
    }

    public function publishPost(Post $post): Publication
    {
        return $this->publications()->create([
            'post_id' => $post->id,
        ]);
    }

    public function schedulePost(Post $post, Carbon $publishAt): ScheduledPost
    {
        return $this->scheduledPosts()->create([
            'post_id' => $post->id,
            'publish_at' => $publishAt,
        ]);
    }
}
