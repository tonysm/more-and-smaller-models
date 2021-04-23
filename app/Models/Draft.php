<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Draft extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function publish(): Publication
    {
        $this->delete();

        return $this->blog->publishPost($this->post);
    }

    public function schedule(Carbon $publishAt): ScheduledPost
    {
        $this->delete();

        return $this->blog->schedulePost($this->post, $publishAt);
    }
}
