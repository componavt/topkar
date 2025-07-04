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
     * @param array $old — старое значение
     * @param array $new — новое значение
     */
    public function logRelationRevision(string $key, string $old, string $new)
    {
        // Только если есть реальное изменение
        if (trim($old) != trim($new)) {
            DB::table('revisions')->insert([
                'revisionable_type' => get_class($this),
                'revisionable_id'   => $this->id,
                'user_id'           => Auth::id(),
                'key'               => $key,
                'old_value'         => $old,
                'new_value'         => $new,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }
    
    /**
     * Логирует изменение связи belongsToMany вручную
     *
     * @param string $key — имя связи (используется как revisions.key)
     * @param array $oldIds — старые ID
     * @param array $newIds — новые ID
     */
    public function logRelationRevisionIds(string $key, array $oldIds, array $newIds, $div = ', ')
    {
        // Только если есть реальное изменение
        if (array_diff($oldIds, $newIds) || array_diff($newIds, $oldIds)) {
            $this->logRelationRevision($key, implode($div, $oldIds), implode($div, $newIds));
        }
    }
}
