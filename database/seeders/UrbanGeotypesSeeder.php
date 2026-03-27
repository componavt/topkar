<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

// php artisan db:seed UrbanGeotypesSeeder

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

            ['name_ru' => 'акватория',      'name_en' => 'water area',      'short_ru' => 'аквт.',     'short_en' => 'W.Area'],
            ['name_ru' => 'аллея',          'name_en' => 'alley',           'short_ru' => 'ал.',       'short_en' => 'Alley'],
            ['name_ru' => 'банка',          'name_en' => 'bank',           'short_ru' => 'банка',     'short_en' => 'Bank'],
            ['name_ru' => 'водохранилище',  'name_en' => 'reservoir',       'short_ru' => 'вдхр.',     'short_en' => 'Res.'],
            ['name_ru' => 'гавань',         'name_en' => 'harbor',          'short_ru' => 'гав.',      'short_en' => 'Harb.'],
            ['name_ru' => 'город',          'name_en' => 'city',            'short_ru' => 'г.',        'short_en' => 'City'],
            ['name_ru' => 'губа',           'name_en' => 'bay',             'short_ru' => 'губа',      'short_en' => 'Bay'],
            ['name_ru' => 'залив',          'name_en' => 'gulf',            'short_ru' => 'зал.',      'short_en' => 'Gulf'],
            ['name_ru' => 'затон',          'name_en' => 'backwater',       'short_ru' => 'затон',     'short_en' => 'Backwater'],
            ['name_ru' => 'источник',       'name_en' => 'source',          'short_ru' => 'ист.',      'short_en' => 'Src.'],
            ['name_ru' => 'канавка',        'name_en' => 'runnel',           'short_ru' => 'канавка',   'short_en' => 'Run.'],
            ['name_ru' => 'канал',          'name_en' => 'canal',           'short_ru' => 'кан.',      'short_en' => 'Canal'],
            ['name_ru' => 'квартал',        'name_en' => 'quarter',         'short_ru' => 'кв.',       'short_en' => 'Qtr.'],
            ['name_ru' => 'ключ',           'name_en' => 'spring',          'short_ru' => 'ключ',      'short_en' => 'Spr.'],
            ['name_ru' => 'магистраль',     'name_en' => 'main road',       'short_ru' => 'маг.',      'short_en' => 'Mainway'],
            ['name_ru' => 'месторождение',  'name_en' => 'deposit',         'short_ru' => 'месторожд.', 'short_en' => 'Dep.'],
            ['name_ru' => 'микрорайон',     'name_en' => 'microdistrict',   'short_ru' => 'мкр.',      'short_en' => 'Micro-dist.'],
            ['name_ru' => 'набережная',     'name_en' => 'embankment',      'short_ru' => 'наб.',      'short_en' => 'Emb.'],
            ['name_ru' => 'озеро',          'name_en' => 'lake',            'short_ru' => 'оз.',       'short_en' => 'Lake'],
            ['name_ru' => 'округ',          'name_en' => 'borough',         'short_ru' => 'окр.',      'short_en' => 'Bor.'],
            ['name_ru' => 'омут',           'name_en' => 'deep pool',       'short_ru' => 'омут',      'short_en' => 'Pool'],
            ['name_ru' => 'остров',         'name_en' => 'island',          'short_ru' => 'о.',        'short_en' => 'Isl.'],
            ['name_ru' => 'плёсы',          'name_en' => 'reaches',         'short_ru' => 'плёсы',     'short_en' => 'Reaches'],
            ['name_ru' => 'переулок',       'name_en' => 'lane',            'short_ru' => 'пер.',      'short_en' => 'Lane'],
            ['name_ru' => 'пороги',         'name_en' => 'rapids',          'short_ru' => 'пороги',    'short_en' => 'Rapids'],
            ['name_ru' => 'порт',           'name_en' => 'port',            'short_ru' => 'порт',      'short_en' => 'Port'],
            ['name_ru' => 'проулок',        'name_en' => 'passageway',      'short_ru' => 'проул.',    'short_en' => 'Passway'],
            ['name_ru' => 'пруд',           'name_en' => 'pond',            'short_ru' => 'пруд',      'short_en' => 'Pond'],
            ['name_ru' => 'разъезд',        'name_en' => 'junction',        'short_ru' => 'разъезд',   'short_en' => 'Jct.'],
            ['name_ru' => 'район',          'name_en' => 'district',        'short_ru' => 'р-н',       'short_en' => 'Dist.'],
            ['name_ru' => 'река',           'name_en' => 'river',           'short_ru' => 'р.',        'short_en' => 'Riv.'],
            ['name_ru' => 'родник',         'name_en' => 'springhead',      'short_ru' => 'родн.',     'short_en' => 'Sprhead.'],
            ['name_ru' => 'ручей',          'name_en' => 'stream',          'short_ru' => 'руч.',      'short_en' => 'Stream'],
            ['name_ru' => 'сад',            'name_en' => 'garden',          'short_ru' => 'сад',       'short_en' => 'Garden'],
            ['name_ru' => 'сквер',          'name_en' => 'small park',      'short_ru' => 'скв.',      'short_en' => 'Sm.park'],
            ['name_ru' => 'спуск',          'name_en' => 'descent',         'short_ru' => 'спуск',     'short_en' => 'Descent'],
            ['name_ru' => 'территория',     'name_en' => 'territory',       'short_ru' => 'тер.',      'short_en' => 'Terr.'],
            ['name_ru' => 'тракт',          'name_en' => 'tract',           'short_ru' => 'тракт',     'short_en' => 'Tract'],
            ['name_ru' => 'тупик',          'name_en' => 'dead end',        'short_ru' => 'туп.',      'short_en' => 'Dead end'],
            ['name_ru' => 'улица',          'name_en' => 'street',          'short_ru' => 'ул.',       'short_en' => 'St.'],
            ['name_ru' => 'часть',          'name_en' => 'part',            'short_ru' => 'ч.',        'short_en' => 'Part'],
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
