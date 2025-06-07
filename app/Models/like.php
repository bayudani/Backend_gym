<?php

namespace App\Models;

use Firefly\FilamentBlog\Models\Post;
use Illuminate\Database\Eloquent\Model;

class like extends Model
{
    protected $table = 'post_likes';

    protected $fillable = [
        'post_id',
        'user_id',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
