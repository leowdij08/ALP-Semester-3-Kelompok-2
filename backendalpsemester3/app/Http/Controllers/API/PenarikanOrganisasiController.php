<?php

namespace App\Http\Controllers\API;

use App\Models\PenarikanOrganisasi;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\RekeningOrganisasi;
use App\Models\PembayaranPerusahaan;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

class PenarikanOrganisasiController extends BaseController
{
    public function getAll(): JsonResponse
    {
        try {
            if (Auth::id()) {
                $dataPenarikan = PenarikanOrganisasi::where("id_organisasi", Auth::user()->organisasi->id_organisasi)->get()->map(function ($penarikan) {
                    $rekening = $penarikan->rekeningorganisasis;
                    return [
                        "rekening" => [
                            "idRekening" => $rekening->id_rekeningorganisasi,
                            "namaBank" => $rekening->namabankorganisasi,
                            "nomorRekening" => $rekening->nomorrekeningorganisasi,
                            "namaPemilik" => $rekening->pemilikrekeningorganisasi
                        ],
                        "totalPenarikan" => $penarikan->biayatotal,
                        "tanggalPenarikan" => $penarikan->tanggalpenarikan,
                        "sudahDiproses" => $penarikan->isProcessed,
                    ];
                });

                return $this->sendResponse($dataPenarikan, 'Withdraw data retrieved successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function getById($id): JsonResponse
    {
        try {
            if (Auth::id()) {
                if (PenarikanOrganisasi::where("id_penarikanorganisasi", $id)->count() > 0) {
                    if (PenarikanOrganisasi::where("id_penarikanorganisasi", $id)->first()->id_organisasi == Auth::user()->organisasi->id_organisasi) {
                        $dataPenarikan = PenarikanOrganisasi::where("id_penarikanorganisasi", $id)->first()->map(function ($penarikan) {
                            $rekening = $penarikan->rekeningorganisasis;
                            return [
                                "rekening" => [
                                    "idRekening" => $rekening->id_rekeningorganisasi,
                                    "namaBank" => $rekening->namabankorganisasi,
                                    "nomorRekening" => $rekening->nomorrekeningorganisasi,
                                    "namaPemilik" => $rekening->pemilikrekeningorganisasi
                                ],
                                "totalPenarikan" => $penarikan->biayatotal,
                                "tanggalPenarikan" => $penarikan->tanggalpenarikan,
                                "sudahDiproses" => $penarikan->isProcessed,
                            ];
                        });

                        return $this->sendResponse($dataPenarikan, 'Withdraw data retrieved successfully.');
                    } else {
                        return $this->sendError('Forbidden.', ['error' => 'Not Your Withdrawal'], 403);
                    }
                } else {
                    return $this->sendError('Withdraw Not Found.', ['error' => 'No Withdrawal With That ID Was Found'], 404);
                }
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function create(Request $request)
    {
        try {
            if (Auth::id()) {
                $validator = Validator::make($request->all(), [
                    'jumlahPenarikan' => 'required|regex:/[0-9]/',
                ]);

                if ($validator->fails()) {
                    return $this->sendError('Validation Error.', $validator->errors(), 400);
                }

                $input = $request->all();
                $danaMasuk = PembayaranPerusahaan::where("id_organisasi", Auth::user()->organisasi->id_organisasi)->get()->map(function ($pembayaran) {
                    return $pembayaran->biayatotal;
                });
                $danaKeluar = PenarikanOrganisasi::where("id_organisasi", Auth::user()->organisasi->id_organisasi)->get()->map(function ($penarikan) {
                    return $penarikan->jumlahdanaditarik;
                });
                $saldo = array_sum(...$danaMasuk) - array_sum(...$danaKeluar);
                if ($saldo < $input['totalPenarikan']) return $this->sendError('Bad Request.', ['error' => 'Withdrawal surpassed limit'], 404);

                $rekening = RekeningOrganisasi::where("id_organisasi", Auth::user()->organisasi)->first();
                $data = [
                    "id_rekeningorganisasi" => $rekening->id_rekeningorganisasi,
                    "jumlahdanaditarik" => $input['totalPenarikan'],
                    "tanggalpenarikan" => now(),
                    "isProcessed" => false
                ];
                $penarikan = PenarikanOrganisasi::create($data);
                $dataPenarikan = $penarikan->get()->map(function ($penarikan) {
                    $rekening = $penarikan->rekeningorganisasis;
                    return [
                        "rekening" => [
                            "idRekening" => $rekening->id_rekeningorganisasi,
                            "namaBank" => $rekening->namabankorganisasi,
                            "nomorRekening" => $rekening->nomorrekeningorganisasi,
                            "namaPemilik" => $rekening->pemilikrekeningorganisasi
                        ],
                        "totalPenarikan" => $penarikan->biayatotal,
                        "tanggalPenarikan" => $penarikan->tanggalpenarikan,
                        "sudahDiproses" => $penarikan->isProcessed,
                    ];
                });

                return $this->sendResponse($dataPenarikan, 'Withdrawal requested successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }
}
