<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    public function run()
    {
        $features = [
            'Đầy đủ nội thất',
            'Có gác',
            'Có kệ bếp',
            'Có máy lạnh',
            'Có máy giặt',
            'Có tủ lạnh',
            'Có thang máy',
            'Không chung chủ',
            'Giờ giấc tự do',
            'Có bảo vệ 24/24',
            'Có hầm để xe',
            'Vị trí & bản đồ',
        ];

        foreach ($features as $featureName) {
            Feature::updateOrCreate(['name' => $featureName]);
        }
    }
}
