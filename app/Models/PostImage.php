<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostImage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'post_id',
        'url',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
