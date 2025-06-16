<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/vietnam-provinces.json');
        if (! file_exists($path)) {
            $this->command->error("File JSON không tìm thấy: {$path}");

            return;
        }

        $data = json_decode(file_get_contents($path), true);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('wards')->truncate();
        DB::table('districts')->truncate();
        DB::table('provinces')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach ($data as $province) {
            $provinceId = DB::table('provinces')->insertGetId([
                'name' => $province['name'],
            ]);

            foreach ($province['districts'] as $district) {
                $districtId = DB::table('districts')->insertGetId([
                    'name' => $district['name'],
                    'province_id' => $provinceId,
                ]);

                foreach ($district['wards'] as $ward) {
                    DB::table('wards')->insert([
                        'name' => $ward['name'],
                        'district_id' => $districtId,
                    ]);
                }
            }
        }

        $this->command->info('✅ Đã seed provinces ('.count($data).'), districts & wards thành công.');
    }
}
