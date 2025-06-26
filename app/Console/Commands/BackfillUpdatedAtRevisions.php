<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BackfillUpdatedAtRevisions extends Command
{
    protected $signature = 'revisions:backfill-updated-at';
    protected $description = 'Добавляет ревизии поля updated_at, если они отсутствуют, но есть другие изменения';

    public function handle()
    {
        $this->info('Начинаю обработку…');

        $revisions = DB::table('revisions')
            ->where('revisionable_type', 'App\Models\Dict\Toponym')
            ->whereNotIn('key', ['created_at', 'updated_at'])
            ->orderBy('revisionable_type')
            ->orderBy('revisionable_id')
            ->orderBy('created_at')
            ->get();

        $grouped = [];
        foreach ($revisions as $rev) {
            $key = $rev->revisionable_type . ':' . $rev->revisionable_id;
            $grouped[$key][] = $rev;
        }

        $inserts = [];
        foreach ($grouped as $key => $revGroup) {
            $lastRev = null;
            $buffer = [];

            foreach ($revGroup as $rev) {
                if (!$lastRev || Carbon::parse($rev->created_at)->diffInMinutes($lastRev->created_at) > 1) {
                    if (count($buffer)) {
                        $inserts[] = $this->makeUpdatedAtRevision($buffer);
                        $buffer = [];
                    }
                }
                $buffer[] = $rev;
                $lastRev = $rev;
            }

            if (count($buffer)) {
                $inserts[] = $this->makeUpdatedAtRevision($buffer);
            }
        }

        foreach ($inserts as $rev) {
            DB::table('revisions')->insert($rev);
        }

        $this->info('Создано записей: ' . count($inserts));
    }

    private function makeUpdatedAtRevision(array $buffer)
    {
        $sample = end($buffer);

        return [
            'revisionable_type' => $sample->revisionable_type,
            'revisionable_id'   => $sample->revisionable_id,
            'user_id'           => $sample->user_id,
            'key'               => 'updated_at',
            'old_value'         => null,
            'new_value'         => Carbon::parse($sample->created_at)->toDateTimeString(),
            'created_at'        => $sample->created_at,
            'updated_at'        => $sample->created_at,
        ];
    }
}
