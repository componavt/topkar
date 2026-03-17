<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Misc\Struct;
use Illuminate\Support\Facades\DB;

class StructsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $structures = [
            'avtoazemu',
            'avtotie',
            'kävelyskujo',
            'vokzualu',
            'azemu',
            'linnannellikkö',
            'liikehymbyry',
            'piätie',
            'kohtu',
            'mikrorajon',
            'sildu',
            'randupiha',
            'randu-uuličču',
            'piirikundu',
            'puusto',
            'tiešuaru',
            'kujo',
            'taimenkazvatuskohtu',
            'lagevo',
            'puoliliikehymbyry',
            'ajotie',
            'prospektu',
            'bokkukujo',
            'ristavussildu',
            'raudutienraiživo',
            'sadu',
            'puustikko',
            'alamägi',
            'azetuskohtu',
            'umbikujo',
            'uuličču',
        ];

        // Вставка записей со structhier_id = 7
        foreach ($structures as $name) {
            $exists = DB::table('structs')
                ->where('name_ru', $name)
                ->where('structhier_id', 7)
                ->exists();

            if (!$exists) {
                Struct::create([
                    'name_ru' => $name,
                    'structhier_id' => 7
                ]);
            }
        }

        // Вставка записей со structhier_id = 8
        foreach ($structures as $name) {
            $exists = DB::table('structs')
                ->where('name_ru', $name)
                ->where('structhier_id', 8)
                ->exists();

            if (!$exists) {
                Struct::create([
                    'name_ru' => $name,
                    'structhier_id' => 8
                ]);
            }
        }
    }
}
