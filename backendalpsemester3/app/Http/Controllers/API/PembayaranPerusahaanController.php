<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PembayaranPerusahaan;
use Illuminate\Support\Facades\Validator;

class PembayaranPerusahaanController extends Controller
{
    // Get all pembayaran
    public function getAll()
    {
        $pembayaran = PembayaranPerusahaan::with(['rekeningperusahaans', 'acaras'])->get();
        return response()->json($pembayaran, 200);
    }

    // Get pembayaran by ID
    public function getById($id)
    {
        $pembayaran = PembayaranPerusahaan::with(['rekeningperusahaans', 'acaras'])->find($id);
        
        if (!$pembayaran) {
            return response()->json(['message' => 'Pembayaran not found'], 404);
        }

        return response()->json($pembayaran, 200);
    }

    // Create a new pembayaran
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_rekeningperusahaan' => 'required|exists:rekening_perusahaan,id_rekeningperusahaan',
            'id_acara' => 'required|exists:event_organisasi,id_acara',
            'biayatotal' => 'required|integer',
            'tanggalpembayaran' => 'required|date',
            'buktipembayaran' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $pembayaran = PembayaranPerusahaan::create($request->all());

        return response()->json($pembayaran, 201);
    }

    // Update pembayaran by ID
    public function update(Request $request, $id)
    {
        $pembayaran = PembayaranPerusahaan::find($id);

        if (!$pembayaran) {
            return response()->json(['message' => 'Pembayaran not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_rekeningperusahaan' => 'sometimes|exists:rekening_perusahaan,id_rekeningperusahaan',
            'id_acara' => 'sometimes|exists:event_organisasi,id_acara',
            'biayatotal' => 'sometimes|integer',
            'tanggalpembayaran' => 'sometimes|date',
            'buktipembayaran' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $pembayaran->update($request->all());

        return response()->json($pembayaran, 200);
    }

    // Delete pembayaran by ID
    public function delete($id)
    {
        $pembayaran = PembayaranPerusahaan::find($id);

        if (!$pembayaran) {
            return response()->json(['message' => 'Pembayaran not found'], 404);
        }

        $pembayaran->delete();

        return response()->json(['message' => 'Pembayaran deleted successfully'], 200);
    }
}
