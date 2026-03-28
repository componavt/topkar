<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\Models\Dict\Street;
use App\Models\Misc\StreetGeometry;

class ImportPetrozavodskStreetGeometry extends Command
{
    protected $signature = 'topkar:import-street-geometry
                            {file=storage/app/osm/petrozavodsk_streets.geojson}';

    protected $description = 'Import local GeoJSON street geometry into street_geometries';

    public function handle(): int
    {
        $file = base_path($this->argument('file'));

        if (!is_file($file)) {
            $this->error("File not found: {$file}");
            return self::FAILURE;
        }

        $json = json_decode(file_get_contents($file), true);

        if (!is_array($json) || ($json['type'] ?? null) !== 'FeatureCollection') {
            $this->error('Invalid GeoJSON FeatureCollection');
            return self::FAILURE;
        }

        $featuresByName = [];

        foreach (($json['features'] ?? []) as $feature) {
            $name = trim((string) data_get($feature, 'properties.name', ''));
            $geometryType = data_get($feature, 'geometry.type');

            if ($name === '' || !in_array($geometryType, ['LineString', 'MultiLineString'], true)) {
                continue;
            }

            //            $norm = $this->normalizeStreetName($name);
            $norm = mb_strtolower(trim($name));

            if (!isset($featuresByName[$norm])) {
                $featuresByName[$norm] = [
                    'source_name' => $name,
                    'features' => [],
                ];
            }

            $featuresByName[$norm]['features'][] = $feature;
        }

        $this->info('Grouped names: ' . count($featuresByName));

        $streets = Street::query()
            ->select(['id', 'name_for_search_ru'])
            ->whereNotNull('name_for_search_ru')
            ->get()
            ->keyBy('name_for_search_ru');

        $imported = 0;

        DB::beginTransaction();

        try {
            foreach ($featuresByName as $norm => $data) {
                // Ищем улицу с точным совпадением
                $street = $streets->get($norm);

                if (!$street) {
                    $this->line('Not matched: ' . $data['source_name']);
                    continue;
                }

                $payload = [
                    'type' => 'FeatureCollection',
                    'features' => $data['features'],
                ];

                StreetGeometry::updateOrCreate(
                    ['street_id' => $street->id],
                    [
                        'source_name' => $data['source_name'],
                        'geojson' => json_encode($payload, JSON_UNESCAPED_UNICODE),
                        'meta' => [
                            'feature_count' => count($payload['features']),
                            'import_source' => 'petrozavodsk_streets.geojson',
                        ],
                    ]
                );

                $imported++;
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        $this->info('Imported streets: ' . $imported);

        return self::SUCCESS;
    }

    protected function normalizeStreetName(string $value): string
    {
        $value = mb_strtolower(trim($value));

        $value = preg_replace('/\b(улица|ул\.|проспект|пр-т|переулок|пер\.|набережная|наб\.|шоссе|бульвар|бул\.|площадь|пл\.|аллея)\b/u', '', $value);
        $value = preg_replace('/\s+/u', ' ', $value);
        $value = trim($value, " \t\n\r\0\x0B-–—,.");

        return $value;
    }
}
