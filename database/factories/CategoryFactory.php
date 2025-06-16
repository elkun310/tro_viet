<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Phòng trọ',
                'Chung cư mini',
                'Căn hộ dịch vụ',
                'Nhà nguyên căn',
                'Chung cư cao cấp',
                'Ký túc xá',
            ]),
        ];
    }
}
