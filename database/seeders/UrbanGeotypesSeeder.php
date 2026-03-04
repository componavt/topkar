<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UrbanGeotypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $urban_geotypes = [
            ['name_ru' => 'проспект', 'name_en' => 'avenue', 'short_ru' => 'пр.', 'short_en' => 'Ave.'],
            ['name_ru' => 'площадь', 'name_en' => 'square', 'short_ru' => 'пл.', 'short_en' => 'Sq.'],
            ['name_ru' => 'проезд', 'name_en' => 'passage', 'short_ru' => 'пр-д', 'short_en' => 'Pass.'],
            ['name_ru' => 'шоссе', 'name_en' => 'highway', 'short_ru' => 'ш.', 'short_en' => 'Hwy.'],
            ['name_ru' => 'парк', 'name_en' => 'park', 'short_ru' => 'парк', 'short_en' => 'Park'],
            ['name_ru' => 'бульвар', 'name_en' => 'boulevard', 'short_ru' => 'б-р', 'short_en' => 'Blvd.'],
        ];

        foreach ($urban_geotypes as $geotype) {
            // Проверяем, не существует ли уже такой тип
            $exists = DB::table('geotypes')
                ->where('name_ru', $geotype['name_ru'])
                ->exists();

            if (!$exists) {
                DB::table('geotypes')->insert([
                    'name_ru' => $geotype['name_ru'],
                    'name_en' => $geotype['name_en'],
                    'short_ru' => $geotype['short_ru'],
                    'short_en' => $geotype['short_en'],
                ]);

                $this->command->info("Создан тип: {$geotype['name_ru']}");
            } else {
                $this->command->warn("Тип уже существует: {$geotype['name_ru']}");
            }
        }
    }
}
