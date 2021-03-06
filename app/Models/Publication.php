<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function unpublish()
    {
        $this->delete();

        return $this->blog->addDraft($this->post);
    }
}
