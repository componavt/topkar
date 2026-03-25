<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StreetsSeeder extends Seeder
{
    // Слова, которые убираются при формировании name_for_search_ru
    private array $geoWords = [
        'улица', 'ул', 'переулок', 'пер', 'проспект', 'просп',
        'проезд', 'проулок', 'площадь', 'бульвар', 'аллея',
        'набережная', 'шоссе', 'тупик', 'спуск', 'сквер',
        'парк', 'территория', 'микрорайон', 'район', 'залив',
        'губа', 'ручей', 'пруд', 'сад', 'магистраль', 'родник',
        'источник', 'мост', 'путь',
    ];

    public function run(): void
    {
        $csvPath = storage_path('app/osm/streets.csv');

        if (!file_exists($csvPath)) {
            $this->command->error("Файл не найден: {$csvPath}");
            return;
        }

        $file = fopen($csvPath, 'r');
        fgetcsv($file); // пропускаем заголовок

        $count = 0;
        $batch = [];

        while (($row = fgetcsv($file)) !== false) {
            [$num, $name, $description, $nameForSearch] = $row;

            if (empty($name)) continue;

            $batch[] = [
                'name_ru'             => $name,
                'name_krl'            => null,
                'name_fi'             => null,
                'name_for_search_ru'  => $this->nameForSearch($name),
                'name_for_search_krl' => null,
                'name_for_search_fi'  => null,
                'geotype_id'          => null,
                'main_info'           => $description ?: null,
                'history'             => null,
                'created_at'          => now(),
                'updated_at'          => now(),
            ];

            $count++;

            // Вставляем пачками по 500 для скорости
            if (count($batch) >= 500) {
                DB::table('streets')->insertOrIgnore($batch);
                $batch = [];
                $this->command->info("Вставлено: {$count}...");
            }
        }

        if (!empty($batch)) {
            DB::table('streets')->insertOrIgnore($batch);
        }

        fclose($file);
        $this->command->info("Готово. Всего вставлено: {$count} записей.");
    }

    private function nameForSearch(string $name): string
    {
        $n = mb_strtolower(trim($name));

        // Убираем гео-слово в начале
        foreach ($this->geoWords as $word) {
            if (str_starts_with($n, $word . ' ')) {
                $n = mb_substr($n, mb_strlen($word) + 1);
                break;
            }
        }

        // Убираем гео-слово в конце
        foreach ($this->geoWords as $word) {
            if (str_ends_with($n, ' ' . $word)) {
                $n = mb_substr($n, 0, mb_strlen($n) - mb_strlen($word) - 1);
                break;
            }
        }

        return trim($n);
    }
}