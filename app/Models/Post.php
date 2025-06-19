<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->code)) {
                $post->code = self::generateUniqueCode();
            }
        });

        static::deleting(function ($post) {
            $post->images()->delete();
            $post->features()->detach();
        });
    }

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

    public static function generateUniqueCode($length = 6)
    {
        do {
            // Sinh code dạng: TV + 6 ký tự chữ số/chữ cái viết hoa
            $code = 'TV'.strtoupper(Str::random($length));
        } while (self::where('code', $code)->exists());

        return $code;
    }
}
