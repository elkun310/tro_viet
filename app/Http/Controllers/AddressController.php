<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function getProvinces(): JsonResponse
    {
        $provinces = Province::select('id', 'name')
            ->get()
            ->map(function ($province) {
                return [
                    'id' => $province->id,
                    'term_id' => $province->id,
                    'name' => $province->name,
                    'location' => 'province',
                ];
            });

        return response()->json([
            'code' => 200,
            'provinces' => $provinces,
        ]);
    }

    public function getDistricts(Request $request): JsonResponse
    {
        $provinceId = $request->query('province_id');
        $query = District::select('id', 'name', 'province_id');

        if ($provinceId) {
            $query->where('province_id', $provinceId);
        }

        $districts = $query->get()
            ->map(function ($district) {
                return [
                    'id' => $district->id,
                    'term_id' => $district->id,
                    'name' => $district->name,
                    'province_id' => $district->province_id,
                    'location' => 'district',
                ];
            });

        return response()->json([
            'code' => 200,
            'districts' => $districts,
        ]);
    }

    public function getWards(Request $request): JsonResponse
    {
        $districtId = $request->query('district_id');
        $query = Ward::select('id', 'name', 'district_id');

        if ($districtId) {
            $query->where('district_id', $districtId);
        }

        $wards = $query->get()
            ->map(function ($ward) {
                return [
                    'id' => $ward->id,
                    'term_id' => $ward->id,
                    'name' => $ward->name,
                    'district_id' => $ward->district_id,
                    'location' => 'ward',
                ];
            });

        return response()->json([
            'code' => 200,
            'wards' => $wards,
        ]);
    }
}
