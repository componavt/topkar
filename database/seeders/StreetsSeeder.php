<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Dict\Street;
use App\Models\Misc\Geotype;

class StreetsSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = storage_path('app/osm/streets.txt');
        $lines    = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $streetTypes = Geotype::streetTypes(); // [geotype_id => geotype_name]

        $records = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;

            [$geotypeId, $sortName] = Street::parseNameForSort($line);

            $records[] = [
                'name_ru'            => $line,
                'name_for_search_ru' => to_search_form($line),
                'sort_name'          => $sortName,
                'geotype_id'         => $geotypeId,
                'created_at'         => now(),
                'updated_at'         => now(),
            ];
        }

        foreach (array_chunk($records, 500) as $chunk) {
            DB::table('streets')->insert($chunk);
        }
    }
}
