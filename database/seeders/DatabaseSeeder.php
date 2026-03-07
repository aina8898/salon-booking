<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Staff;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // メニュー（サービス）
        $services = [
            ['service_name' => 'カット',                 'duration_minutes' => 60,  'price' => 4000],
            ['service_name' => 'カラー',               'duration_minutes' => 90,  'price' => 6000],
            ['service_name' => 'パーマ',               'duration_minutes' => 120, 'price' => 8000],
            ['service_name' => 'カット＆カラー',        'duration_minutes' => 150, 'price' => 9000],
            ['service_name' => 'カット＆パーマ',        'duration_minutes' => 180, 'price' => 10000],
            ['service_name' => 'カット＆パーマ＆カラー', 'duration_minutes' => 210, 'price' => 13000],
        ];

        foreach ($services as $data) {
            Service::firstOrCreate(
                ['service_name' => $data['service_name']],
                ['duration_minutes' => $data['duration_minutes'], 'price' => $data['price']]
            );
        }

        // スタッフ
        $staffs = [
            ['name' => '湊﨑 紗夏',   'specialization' => 'スタイリスト'],
            ['name' => '平井 もも',   'specialization' => 'スタイリスト'],
            ['name' => '名井 南',     'specialization' => 'スタイリスト'],
            ['name' => '中本 悠太',   'specialization' => 'トップスタイリスト'],
            ['name' => '大﨑 将太郎', 'specialization' => 'トップスタイリスト'],
        ];

        foreach ($staffs as $data) {
            Staff::firstOrCreate(
                ['name' => $data['name']],
                ['specialization' => $data['specialization']]
            );
        }
    }
}
