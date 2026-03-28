<?php

namespace App\Http\Controllers\Misc;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

use App\Models\Dict\Street;
use App\Models\Misc\StreetGeometry;
use Illuminate\Http\Request;

class StreetGeometryController extends Controller
{
    public function show(Street $street)
    {
        $geometry = StreetGeometry::where('street_id', $street->id)->first();

        if (!$geometry) {
            return response()->json(null);
        }

        return response()->json([
            'geojson' => $geometry->geojson,
        ]);
    }

    public function store(Request $request, Street $street)
    {
        $request->validate([
            'geojson' => 'required|array',
        ]);

        try {
            $geometry = StreetGeometry::updateOrCreate(
                ['street_id' => $street->id],
                [
                    'source_name' => $street->name_ru,
                    'geojson'     => json_encode($request->geojson, JSON_UNESCAPED_UNICODE),
                    'meta'        => [
                        'feature_count' => count($request->geojson['features'] ?? []),
                        'import_source' => 'manual',
                    ],
                ]
            );

            /*            Log::info('StreetGeometry result', [
                'street_id'   => $street->id,
                'id'          => $geometry->id,
                'was_created' => $geometry->wasRecentlyCreated, // true=создана, false=обновлена
                'updated_at'  => $geometry->updated_at,
            ]);*/
        } catch (\Exception $e) {
            Log::error('StreetGeometry error', ['message' => $e->getMessage()]);
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }

        return response()->json(['ok' => true]);
    }
}
