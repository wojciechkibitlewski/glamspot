<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Region;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $term = trim((string) $request->query('q', ''));
        $regionId = (int) $request->query('region_id', 0);
        $regionName = trim((string) $request->query('region', ''));

        if (mb_strlen($term) < 2) {
            return response()->json(['data' => []]);
        }

        $query = City::query();

        if ($regionId > 0) {
            $query->where('region_id', $regionId);
        } elseif ($regionName !== '') {
            $region = Region::query()->where('name', $regionName)->first();
            if ($region) {
                $query->where('region_id', $region->id);
            }
        }

        $results = $query
            ->where('name', 'like', "%{$term}%")
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json(['data' => $results]);
    }
}
