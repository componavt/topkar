<?php

namespace App\Traits\Modify;

use Illuminate\Support\Facades\Auth;
use DB;

trait LogsRelationRevisions
{
    /**
     * Логирует изменение связи belongsToMany вручную
     *
     * @param string $key — имя связи (используется как revisions.key)
     * @param array $oldIds — старые ID
     * @param array $newIds — новые ID
     */
    public function logRelationRevision(string $key, array $oldIds, array $newIds)
    {
        // Только если есть реальное изменение
        if (array_diff($oldIds, $newIds) || array_diff($newIds, $oldIds)) {
            DB::table('revisions')->insert([
                'revisionable_type' => get_class($this),
                'revisionable_id'   => $this->id,
                'user_id'           => Auth::id(),
                'key'               => $key,
                'old_value'         => implode(',', $oldIds),
                'new_value'         => implode(',', $newIds),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }
}
