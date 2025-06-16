<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'code',
        'category_id',
        'title',
        'description',
        'price',
        'area',
        'address',
        'ward_id',
        'is_featured',
        'status',
    ];

    // 🔗 User đăng bài
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔗 Danh mục
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 🔗 Phường/xã
    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    // 🔗 Quận/huyện thông qua ward
    public function district()
    {
        return $this->hasOneThrough(District::class, Ward::class, 'id', 'id', 'ward_id', 'district_id');
    }

    // 🔗 Tỉnh/thành phố thông qua ward > district
    public function province()
    {
        return $this->hasOneThrough(Province::class, District::class, 'id', 'id', 'district_id', 'province_id');
    }

    // 🔗 Ảnh bài đăng
    public function images()
    {
        return $this->hasMany(PostImage::class);
    }

    // 🔗 Các đặc điểm
    public function features()
    {
        return $this->belongsToMany(Feature::class, 'post_feature');
    }
}
