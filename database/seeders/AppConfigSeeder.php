<?php

namespace Database\Seeders;

use App\Models\AppConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppConfig::create([
            'app_version' => '1.0.0',
            'app_store_url' => '',
            'play_store_url' => '',
            'email' => '',
            'whatsapp' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
