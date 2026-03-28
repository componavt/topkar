<?php

namespace App\Traits\Modify;

trait StreetModify
{
    public static function storeData(array $data, $request)
    {
        $street = self::create($data);
        $street->updateAddInfo($data, $request);

        return $street;
    }

    public function updateData(array $data, $request)
    {
        $this->fill($data);
        $this->save();

        $this->updateAddInfo($data, $request);
    }

    public function updateAddInfo(array $data, $request)
    {
        $this->syncSortName();
        foreach (['ru', 'krl', 'fi'] as $l) {
            $this->{'name_for_search_' . $l} = to_search_form($this->{'name_' . $l});
        }
        $this->save();
        $this->updateStructs($request);
        $this->logTouch();
    }

    public function updateStructs($request)
    {
        $old = $this->structsForHistory();

        $structs = array_filter((array)$request->structs, 'strlen');    // strlen удаляет пустые элементы
        $this->structs()->sync($structs);

        $this->logRelationRevisionIds('struct', $old, $this->structsForHistory());
    }

    public function remove()
    {
        $this->structs()->detach();
        $this->delete();
    }
}
