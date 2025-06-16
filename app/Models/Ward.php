<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ward extends Model
{
    protected $fillable = [
        'name',
        'code',
        'district_id'
    ];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
} 