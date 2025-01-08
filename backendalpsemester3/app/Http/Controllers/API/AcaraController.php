<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Models\UserOrganisasi;
use App\Models\PenanggungJawabOrganisasi;
use App\Models\UserPerusahaan;
use App\Models\PenanggungJawabPerusahaan;
use App\Models\Acara;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

class AcaraController extends BaseController
{
    public function getAll(): JsonResponse
    {
        try {
            if (Auth::id()) {
                $dataAcara = Acara::all()->map(function ($acara) {
                    $userOrganisasi = $acara->organisasis;
                    return [
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
                    ];
                });

                return $this->sendResponse($dataAcara, 'Events retrieved successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function getById($idAcara): JsonResponse
    {
        try {
            if (Auth::id()) {
                if (Acara::where("id_acara", $idAcara)->count() > 0) {
                    $dataAcara = Acara::where("id_acara", $idAcara)->get()->map(function ($acara) {
                        $userOrganisasi = $acara->organisasis;
                        return [
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
                        ];
                    });

                    return $this->sendResponse($dataAcara, 'Events retrieved successfully.');
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

    public function filter(Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                $filters = $request->all();
                $dataAcara = Acara::where("biayadibutuhkan", (isset($filters["minHarga"]) ? ">=" : "!="), (isset($filters["minHarga"]) ? $filters["minHarga"] : ""))
                    ->where("biayadibutuhkan", (isset($filters["maxHarga"]) ? "<=" : "!="), (isset($filters["maxHarga"]) ? $filters["maxHarga"] : ""))
                    ->where("kotaberlangsung", (isset($filters["lokasi"]) ? "=" : "!="), (isset($filters["lokasi"]) ? $filters["lokasi"] : ""))
                    ->where("kegiatanacara", (isset($filters["kegiatan"]) ? "=" : "!="), (isset($filters["kegiatan"]) ? $filters["kegiatan"] : ""))
                    ->get()->map(function ($acara) {
                        $userOrganisasi = $acara->organisasis;
                        return [
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
                        ];
                    });

                return $this->sendResponse($dataAcara, 'Events filtered successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function update($idAcara, Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                if (Acara::where("id_acara", $idAcara)->count() > 0) {
                    if (Acara::where("id_acara", $idAcara)->first()->id_organisasi == Auth::user()->id) {
                        $validator = Validator::make($request->all(), [
                            'namaAcara' => 'required',
                            'tanggalAcara' => 'required|date|date_format:Y-m-d',
                            'lokasiAcara' => 'required',
                            'biayaDibutuhkan' => 'required|regex:/[0-9]/',
                            'kotaBerlangsung' => 'required|in:Makassar,Jakarta,Surabaya',
                            'kegiatanAcara' => 'required|in:Gunung,Pantai,Hutan',
                        ]);

                        if ($validator->fails()) {
                            return $this->sendError('Validation Error.', $validator->errors(), 400);
                        }

                        $input = $request->all();
                        $data = [
                            "namaacara" => $input['namaAcara'],
                            "tanggalacara" => $input['tanggalAcara'],
                            "lokasiacara" => $input['lokasiAcara'],
                            "biayadibutuhkan" => $input['biayaDibutuhkan'],
                            "kegiatanacara" => $input['kegiatanAcara'],
                            "kotaberlangsung" => $input['kotaBerlangsung'],
                        ];
                        if (isset($input['posterEvent'])) $data["poster_event"] = $input['posterEvent'];
                        Acara::where('id_acara', $idAcara)->update($data);
                        $dataAcara = Acara::where("id_acara", $idAcara)->get()->map(function ($acara) {
                            $userOrganisasi = $acara->organisasis;
                            return [
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
                            ];
                        });

                        return $this->sendResponse($dataAcara, 'Event updated successfully.');
                    } else {
                        return $this->sendError('Forbidden.', ['error' => 'Not Your Event'], 403);
                    }
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

    public function search(Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                $filters = $request->all();
                $dataAcara = Acara::where("biayadibutuhkan", (isset($filters["minHarga"]) ? ">=" : "!="), (isset($filters["minHarga"]) ? $filters["minHarga"] : ""))
                    ->where("biayadibutuhkan", (isset($filters["maxHarga"]) ? "<=" : "!="), (isset($filters["maxHarga"]) ? $filters["maxHarga"] : ""))
                    ->where("kotaberlangsung", (isset($filters["lokasi"]) ? "=" : "!="), (isset($filters["lokasi"]) ? $filters["lokasi"] : ""))
                    ->where("kegiatanacara", (isset($filters["kegiatan"]) ? "=" : "!="), (isset($filters["kegiatan"]) ? $filters["kegiatan"] : ""))
                    ->get()->map(function ($acara) {
                        return [
                            "id_acara" => $acara->id_acara,
                            "id_organisasi" => $acara->id_organisasi,
                            "nama_acara" => $acara->namaacara,
                            "tanggal_acara" => $acara->tanggalacara,
                            "lokasi_acara" => $acara->lokasiacara,
                            "biaya_dibutuhkan" => $acara->biayadibutuhkan,
                            "kegiatan_acara" => $acara->kegiatanacara,
                            "kota_berlangsung" => $acara->kotaberlangsung,
                            "poster_acara" => $acara->poster_event,
                        ];
                    });

                return $this->sendResponse($dataAcara, 'Events filtered successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }
}
