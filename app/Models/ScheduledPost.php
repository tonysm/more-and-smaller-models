<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledPost extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'publish_at' => 'datetime',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
