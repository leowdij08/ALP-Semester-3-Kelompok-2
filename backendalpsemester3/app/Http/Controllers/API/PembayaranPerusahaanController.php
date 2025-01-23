<?php

namespace App\Http\Controllers\API;

use App\Models\PembayaranPerusahaan;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Acara;
use App\Models\RekeningPerusahaan;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

class PembayaranPerusahaanController extends BaseController
{
    public function getAll(): JsonResponse
    {
        try {
            if (Auth::id()) {
                $dataPembayaran = PembayaranPerusahaan::where("id_perusahaan", UserPerusahaan::where("id_user", Auth::user()->id)->id_perusahaan)->get()->map(function ($pembayaran) {
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
                    return $this->sendError('Payment Not Found.', ['error' => 'No Payment With That ID Was Found'], 404);
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
        try {
            if (Auth::id()) {
                if (Acara::where("id_acara", $idAcara)->count() > 0) {
                    $validator = Validator::make($request->all(), [
                        'biayaTotal' => 'required|regex:/[0-9]/',
                        'buktiPembayaran' => 'required'
                    ]);

                    if ($validator->fails()) {
                        return $this->sendError('Validation Error.', $validator->errors(), 400);
                    }

                    $input = $request->all();
                    $rekening = RekeningPerusahaan::where("id_perusahaan", UserPerusahaan::where("id_user", Auth::user()->id))->first();
                    $data = [
                        "biayatotal" => $input['biayaTotal'],
                        "id_rekeningperusahaan" => $rekening->id_rekeningperusahaan,
                        "id_acara" => $idAcara,
                        "tanggalpembayaran" => now(),
                        "buktipembayaran" => $input['buktiPembayaran']
                    ];
                    $pembayaran = PembayaranPerusahaan::create($data);
                    $dataPembayaran = $pembayaran->get()->map(function ($item) {
                        $rekening = $item->rekeningperusahaans;
                        $acara = $item->acaras;
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
                            "totalPembayaran" => $item->biayatotal,
                            "tanggalPembayaran" => $item->tanggalpembayaran,
                            "buktiPembayaran" => $item->buktipembayaran,
                        ];
                    });

                    return $this->sendResponse($dataPembayaran, 'Payment created successfully.');
                } else {
                    return $this->sendError('Event Not Found.', ['error' => 'No Event With That ID Was Found'], 404);
                }
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }
}
