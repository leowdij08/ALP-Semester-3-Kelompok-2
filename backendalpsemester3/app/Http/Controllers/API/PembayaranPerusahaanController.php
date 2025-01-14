<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PembayaranPerusahaan;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Models\UserOrganisasi;
use App\Models\PenanggungJawabOrganisasi;
use App\Models\UserPerusahaan;
use App\Models\PenanggungJawabPerusahaan;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

class PembayaranPerusahaanController extends BaseController
{
    public function getAll(): JsonResponse
    {
        try {
            if (Auth::id()) {
                $dataPembayaran = PembayaranPerusahaan::where("id_perusahaan", Auth::user()->perusahaan->id_perusahaan)->all()->map(function ($pembayaran) {
                    $rekening = $pembayaran->rekeningperusahaans;
                    $acara = $pembayaran->acaras;
                    $userOrganisasi = $acara->organisasis;
                    return [
                        "rekening" => [
                            "idRekening" => $rekening->id_rekeningperusahaan,
                            "namaBank" => $rekening->namabankperusahaan,
                            "nomorRekening" => $rekening->nomorrekeningperusahaan,
                            "namaPemilik" => $rekening->pemilikrekeningperusahaan
                        ],
                        "acara" => [
                            "id_acara" => $acara->id_acara,
                            "nama_acara" => $acara->namaacara,
                            "tanggal_acara" => $acara->tanggalacara,
                            "lokasi_acara" => $acara->lokasiacara,
                            "biaya_dibutuhkan" => $acara->biayadibutuhkan,
                            "kegiatan_acara" => $acara->kegiatanacara,
                            "kota_berlangsung" => $acara->kotaberlangsung,
                            "poster_acara" => $acara->poster_event,
                            "organisasi" => [
                                "id_organisasi" => $userOrganisasi->id_organisasi,
                                "nama_organisasi" => $userOrganisasi->namaorganisasi,
                                "kota_domisili_organisasi" => $userOrganisasi->kotadomisiliorganisasi,
                                "nomor_telepon_organisasi" => $userOrganisasi->nomorteleponorganisasi
                            ]
                        ],
                        "totalPembayaran" => $pembayaran->biayatotal,
                        "tanggalPembayaran" => $pembayaran->tanggalpembayaran,
                        "buktiPembayaran" => $pembayaran->buktipembayaran,
                    ];
                });

                return $this->sendResponse($dataPembayaran, 'Payments retrieved successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }
    public function getById($idPembayaran): JsonResponse
    {
        try {
            if (Auth::id()) {
                if (PembayaranPerusahaan::where("id_pembayaranperusahaan", $idPembayaran)->count() > 0) {
                    if (PembayaranPerusahaan::where("id_pembayaranperusahaan", $idPembayaran)->first()->rekeningperusahaans->perusahaans->id_user == Auth::user()->id) {
                        $dataPembayaran = PembayaranPerusahaan::where("id_pembayaranperusahaan", $idPembayaran)->first()->map(function ($pembayaran) {
                            $rekening = $pembayaran->rekeningperusahaans;
                            $acara = $pembayaran->acaras;
                            $userOrganisasi = $acara->organisasis;
                            return [
                                "rekening" => [
                                    "idRekening" => $rekening->id_rekeningperusahaan,
                                    "namaBank" => $rekening->namabankperusahaan,
                                    "nomorRekening" => $rekening->nomorrekeningperusahaan,
                                    "namaPemilik" => $rekening->pemilikrekeningperusahaan
                                ],
                                "acara" => [
                                    "id_acara" => $acara->id_acara,
                                    "nama_acara" => $acara->namaacara,
                                    "tanggal_acara" => $acara->tanggalacara,
                                    "lokasi_acara" => $acara->lokasiacara,
                                    "biaya_dibutuhkan" => $acara->biayadibutuhkan,
                                    "kegiatan_acara" => $acara->kegiatanacara,
                                    "kota_berlangsung" => $acara->kotaberlangsung,
                                    "poster_acara" => $acara->poster_event,
                                    "organisasi" => [
                                        "id_organisasi" => $userOrganisasi->id_organisasi,
                                        "nama_organisasi" => $userOrganisasi->namaorganisasi,
                                        "kota_domisili_organisasi" => $userOrganisasi->kotadomisiliorganisasi,
                                        "nomor_telepon_organisasi" => $userOrganisasi->nomorteleponorganisasi
                                    ]
                                ],
                                "totalPembayaran" => $pembayaran->biayatotal,
                                "tanggalPembayaran" => $pembayaran->tanggalpembayaran,
                                "buktiPembayaran" => $pembayaran->buktipembayaran,
                            ];
                        });

                        return $this->sendResponse($dataPembayaran, 'Payments retrieved successfully.');
                    } else {
                        return $this->sendError('Forbidden.', ['error' => 'Not Your Payment'], 403);
                    }
                } else {
                    return $this->sendError('Event Not Found.', ['error' => 'No Payment With That ID Was Found'], 404);
                }
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function create(Request $request, $idAcara)
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
