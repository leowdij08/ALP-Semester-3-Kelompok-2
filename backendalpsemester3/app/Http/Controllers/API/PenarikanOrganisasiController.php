<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PenarikanOrganisasi;

class PenarikanOrganisasiController extends Controller
{
    public function getAll()
    {
        $penarikans = PenarikanOrganisasi::with('rekeningorganisasis')->get();
        return response()->json($penarikans, 200);
    }

    public function getById($id)
    {
        $penarikan = PenarikanOrganisasi::with('rekeningorganisasis')->find($id);

        if (!$penarikan) {
            return response()->json(['message' => 'Penarikan not found'], 404);
        }

        return response()->json($penarikan, 200);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'id_rekeningorganisasi' => 'required|exists:rekening_organisasi,id_rekeningorganisasi',
            'jumlahdanaditarik' => 'required|integer|min:1',
            'tanggalpenarikan' => 'required|date',
            'buktipenarikan' => 'required|string',
        ]);

        $penarikan = PenarikanOrganisasi::create($validated);

        return response()->json(['message' => 'Penarikan created successfully', 'data' => $penarikan], 201);
    }

    public function update(Request $request, $id)
    {
        $penarikan = PenarikanOrganisasi::find($id);

        if (!$penarikan) {
            return response()->json(['message' => 'Penarikan not found'], 404);
        }

        $validated = $request->validate([
            'id_rekeningorganisasi' => 'sometimes|exists:rekening_organisasi,id_rekeningorganisasi',
            'jumlahdanaditarik' => 'sometimes|integer|min:1',
            'tanggalpenarikan' => 'sometimes|date',
            'buktipenarikan' => 'sometimes|string',
        ]);

        $penarikan->update($validated);

        return response()->json(['message' => 'Penarikan updated successfully', 'data' => $penarikan], 200);
    }

    public function delete($id)
    {
        $penarikan = PenarikanOrganisasi::find($id);

        if (!$penarikan) {
            return response()->json(['message' => 'Penarikan not found'], 404);
        }

        $penarikan->delete();

        return response()->json(['message' => 'Penarikan deleted successfully'], 200);
    }
}
