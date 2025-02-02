<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\PembayaranPerusahaan;
use App\Models\Acara;
use App\Models\UserPerusahaan;
use App\Models\UserOrganisasi;
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
                    $biayaDikumpulkan = PembayaranPerusahaan::where('id_acara', $acara->id_acara)->count() > 0 ? array_sum(...PembayaranPerusahaan::where('id_acara', $acara->id_acara)->get()->map(function ($biaya) {
                        return $biaya->biayatotal;
                    })) : 0;
                    $biayaDibutuhkan = $acara->biayadibutuhkan <= $biayaDikumpulkan ? 0 : $acara->biayadibutuhkan - $biayaDikumpulkan;
                    return [
                        "id_acara" => $acara->id_acara,
                        "nama_acara" => $acara->namaacara,
                        "tanggal_acara" => $acara->tanggalacara,
                        "lokasi_acara" => $acara->lokasiacara,
                        "biaya_dibutuhkan" => $biayaDibutuhkan,
                        "kegiatan_acara" => $acara->kegiatanacara,
                        "kota_berlangsung" => $acara->kotaberlangsung,
                        "poster_acara" => $acara->poster_event,
                        "proposal" => $acara->proposal,
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

    public function getIncoming(): JsonResponse
    {
        try {
            if (Auth::id()) {
                $dataAcara = Acara::where("tanggalacara", ">", now())->get()->map(function ($acara) {
                    $userOrganisasi = $acara->organisasis;
                    $biayaDikumpulkan = PembayaranPerusahaan::where('id_acara', $acara->id_acara)->count() > 0 ? array_sum(...PembayaranPerusahaan::where('id_acara', $acara->id_acara)->get()->map(function ($biaya) {
                        return $biaya->biayatotal;
                    })) : 0;
                    $biayaDibutuhkan = $acara->biayadibutuhkan <= $biayaDikumpulkan ? 0 : $acara->biayadibutuhkan - $biayaDikumpulkan;
                    return [
                        "id_acara" => $acara->id_acara,
                        "nama_acara" => $acara->namaacara,
                        "tanggal_acara" => $acara->tanggalacara,
                        "lokasi_acara" => $acara->lokasiacara,
                        "biaya_dibutuhkan" => $biayaDibutuhkan,
                        "kegiatan_acara" => $acara->kegiatanacara,
                        "kota_berlangsung" => $acara->kotaberlangsung,
                        "poster_acara" => $acara->poster_event,
                        "proposal" => $acara->proposal,
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
                        $biayaDikumpulkan = PembayaranPerusahaan::where('id_acara', $acara->id_acara)->count() > 0 ? array_sum(...PembayaranPerusahaan::where('id_acara', $acara->id_acara)->get()->map(function ($biaya) {
                            return $biaya->biayatotal;
                        })) : 0;
                        $biayaDibutuhkan = $acara->biayadibutuhkan <= $biayaDikumpulkan ? 0 : $acara->biayadibutuhkan - $biayaDikumpulkan;
                        $perusahaanKerjasama = PembayaranPerusahaan::where('id_acara', $acara->id_acara)->count() > 0 ? PembayaranPerusahaan::where('id_acara', $acara->id_acara)->groupBy("id_rekeningperusahaan")->get()->map(function ($pembayaran) {
                            $perusahaan = $pembayaran->rekeningperusahaans->perusahaans;
                            return [
                                "namaPerusahaan" => $perusahaan->namaperusahaan,
                                "jumlahSponsor" => array_sum(...PembayaranPerusahaan::where('id_acara', $pembayaran->id_acara)->where("id_rekeningperusahaan", $pembayaran->rekeningperusahaans->id_rekeningperusahaan)->get()->map(function ($biaya) {
                                    return $biaya->biayatotal;
                                }))
                            ];
                        }) : [];
                        return [
                            "id_acara" => $acara->id_acara,
                            "nama_acara" => $acara->namaacara,
                            "tanggal_acara" => $acara->tanggalacara,
                            "lokasi_acara" => $acara->lokasiacara,
                            "biaya_dibutuhkan" => $biayaDibutuhkan,
                            "kegiatan_acara" => $acara->kegiatanacara,
                            "kota_berlangsung" => $acara->kotaberlangsung,
                            "poster_acara" => $acara->poster_event,
                            "proposal" => $acara->proposal,
                            "organisasi" => [
                                "id_organisasi" => $userOrganisasi->id_organisasi,
                                "nama_organisasi" => $userOrganisasi->namaorganisasi,
                                "kota_domisili_organisasi" => $userOrganisasi->kotadomisiliorganisasi,
                                "nomor_telepon_organisasi" => $userOrganisasi->nomorteleponorganisasi
                            ],
                            "perusahaan_kerjasama" => $perusahaanKerjasama
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
                $dataAcara = Acara::where("tanggalacara", ">", now())->where("biayadibutuhkan", (isset($filters["minHarga"]) ? ">=" : "!="), (isset($filters["minHarga"]) ? $filters["minHarga"] : ""))
                    ->where("biayadibutuhkan", (isset($filters["maxHarga"]) ? "<=" : "!="), (isset($filters["maxHarga"]) ? $filters["maxHarga"] : ""))
                    ->where("kotaberlangsung", (isset($filters["lokasi"]) ? "=" : "!="), (isset($filters["lokasi"]) ? $filters["lokasi"] : ""))
                    ->where("kegiatanacara", (isset($filters["kegiatan"]) ? "=" : "!="), (isset($filters["kegiatan"]) ? $filters["kegiatan"] : ""))
                    ->get()->map(function ($acara) {
                        $userOrganisasi = $acara->organisasis;
                        $biayaDikumpulkan = PembayaranPerusahaan::where('id_acara', $acara->id_acara)->count() > 0 ? array_sum(...PembayaranPerusahaan::where('id_acara', $acara->id_acara)->get()->map(function ($biaya) {
                            return $biaya->biayatotal;
                        })) : 0;
                        $biayaDibutuhkan = $acara->biayadibutuhkan <= $biayaDikumpulkan ? 0 : $acara->biayadibutuhkan - $biayaDikumpulkan;
                        return [
                            "id_acara" => $acara->id_acara,
                            "nama_acara" => $acara->namaacara,
                            "tanggal_acara" => $acara->tanggalacara,
                            "lokasi_acara" => $acara->lokasiacara,
                            "biaya_dibutuhkan" => $biayaDibutuhkan,
                            "kegiatan_acara" => $acara->kegiatanacara,
                            "kota_berlangsung" => $acara->kotaberlangsung,
                            "poster_acara" => $acara->poster_event,
                            "proposal" => $acara->proposal,
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
                    if (Acara::where("id_acara", $idAcara)->first()->id_organisasi == Auth::user()->organisasi->id_organisasi) {
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
                        if (isset($input['proposal'])) $data["proposal"] = $input['proposal'];
                        Acara::where('id_acara', $idAcara)->update($data);
                        $dataAcara = Acara::where("id_acara", $idAcara)->get()->map(function ($acara) {
                            $userOrganisasi = $acara->organisasis;
                            $biayaDikumpulkan = PembayaranPerusahaan::where('id_acara', $acara->id_acara)->count() > 0 ? array_sum(...PembayaranPerusahaan::where('id_acara', $acara->id_acara)->get()->map(function ($biaya) {
                                return $biaya->biayatotal;
                            })) : 0;
                            $biayaDibutuhkan = $acara->biayadibutuhkan <= $biayaDikumpulkan ? 0 : $acara->biayadibutuhkan - $biayaDikumpulkan;
                            return [
                                "id_acara" => $acara->id_acara,
                                "nama_acara" => $acara->namaacara,
                                "tanggal_acara" => $acara->tanggalacara,
                                "lokasi_acara" => $acara->lokasiacara,
                                "biaya_dibutuhkan" => $biayaDibutuhkan,
                                "kegiatan_acara" => $acara->kegiatanacara,
                                "kota_berlangsung" => $acara->kotaberlangsung,
                                "poster_acara" => $acara->poster_event,
                                "proposal" => $acara->proposal,
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

    public function create(Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
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
                $idOrganisasi = Auth::user()->id;
                $data = [
                    "namaacara" => $input['namaAcara'],
                    "tanggalacara" => $input['tanggalAcara'],
                    "lokasiacara" => $input['lokasiAcara'],
                    "biayadibutuhkan" => $input['biayaDibutuhkan'],
                    "kegiatanacara" => $input['kegiatanAcara'],
                    "kotaberlangsung" => $input['kotaBerlangsung'],
                    "id_organisasi" => $idOrganisasi,
                ];
                if (isset($input['posterEvent'])) $data["poster_event"] = $input['posterEvent'];
                if (isset($input['proposal'])) $data["proposal"] = $input['proposal'];
                $acara = Acara::create($data);
                $userOrganisasi = $acara->organisasis;
                $dataAcara = [
                    "id_acara" => $acara->id_acara,
                    "nama_acara" => $acara->namaacara,
                    "tanggal_acara" => $acara->tanggalacara,
                    "lokasi_acara" => $acara->lokasiacara,
                    "biaya_dibutuhkan" => $acara->biayadibutuhkan,
                    "kegiatan_acara" => $acara->kegiatanacara,
                    "kota_berlangsung" => $acara->kotaberlangsung,
                    "poster_acara" => $acara->poster_event,
                    "proposal" => $acara->proposal,
                    "organisasi" => [
                        "id_organisasi" => $userOrganisasi->id_organisasi,
                        "nama_organisasi" => $userOrganisasi->namaorganisasi,
                        "kota_domisili_organisasi" => $userOrganisasi->kotadomisiliorganisasi,
                        "nomor_telepon_organisasi" => $userOrganisasi->nomorteleponorganisasi
                    ]
                ];

                return $this->sendResponse($dataAcara, 'Event updated successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function search(Request $request, $keyword): JsonResponse
    {
        try {
            if (Auth::id()) {
                $dataAcara = Acara::join('user_organisasi', 'event_organisasi.id_organisasi', '=', 'user_organisasi.id_organisasi')
                ->whereRaw("concat(event_organisasi.namaacara, event_organisasi.tanggalacara, event_organisasi.lokasiacara, event_organisasi.biayadibutuhkan, event_organisasi.kegiatanacara, event_organisasi.kotaberlangsung) like ?", ["%$keyword%"])
                ->orWhereRaw("concat(user_organisasi.namaorganisasi) like ?", ["%$keyword%"])
                ->where("tanggalacara", ">", now())
                    ->get()->map(function ($acara) {
                        $biayaDikumpulkan = PembayaranPerusahaan::where('id_acara', $acara->id_acara)->count() > 0 ? array_sum(...PembayaranPerusahaan::where('id_acara', $acara->id_acara)->get()->map(function ($biaya) {
                            return $biaya->biayatotal;
                        })) : 0;
                        $biayaDibutuhkan = $acara->biayadibutuhkan <= $biayaDikumpulkan ? 0 : $acara->biayadibutuhkan - $biayaDikumpulkan;
                        return [
                            "id_acara" => $acara->id_acara,
                            "id_organisasi" => $acara->id_organisasi,
                            "nama_acara" => $acara->namaacara,
                            "tanggal_acara" => $acara->tanggalacara,
                            "lokasi_acara" => $acara->lokasiacara,
                            "biaya_dibutuhkan" => $biayaDibutuhkan,
                            "kegiatan_acara" => $acara->kegiatanacara,
                            "kota_berlangsung" => $acara->kotaberlangsung,
                            "poster_acara" => $acara->poster_event,
                            "nama_organisasi" => $acara->organisasis->namaorganisasi,
                            "proposal" => $acara->proposal
                        ];
                    });

                return $this->sendResponse($dataAcara, 'Events searched successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function delete($idAcara, Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                if (Acara::where("id_acara", $idAcara)->count() > 0) {
                    if (Acara::where("id_acara", $idAcara)->first()->id_organisasi == Auth::user()->id) {
                        return $this->sendResponse(['isDeleted' => Acara::where('id_acara', $idAcara)->delete() ? true : false], 'Event deleted successfully.');
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

    public function getByOrganisasi($idOrganisasi, $filter = null): JsonResponse
    {
        try {
            if (Auth::id()) {
                if (UserOrganisasi::where("id_organisasi", $idOrganisasi)->count() > 0) {
                    if ($filter != null) $filtered = strtolower($filter) == "old" ? Acara::whereDate("tanggalacara", "<", now()) : Acara::whereDate("tanggalacara", ">=", now());
                    else $filtered = Acara::class;
                    $dataAcara = $filtered->where("id_organisasi", $idOrganisasi)->map(function ($acara) {
                        $userOrganisasi = $acara->organisasis;
                        $biayaDikumpulkan = PembayaranPerusahaan::where('id_acara', $acara->id_acara)->count() > 0 ? array_sum(...PembayaranPerusahaan::where('id_acara', $acara->id_acara)->get()->map(function ($biaya) {
                            return $biaya->biayatotal;
                        })) : 0;
                        $biayaDibutuhkan = $acara->biayadibutuhkan <= $biayaDikumpulkan ? 0 : $acara->biayadibutuhkan - $biayaDikumpulkan;
                        return [
                            "id_acara" => $acara->id_acara,
                            "nama_acara" => $acara->namaacara,
                            "tanggal_acara" => $acara->tanggalacara,
                            "lokasi_acara" => $acara->lokasiacara,
                            "biaya_dibutuhkan" => $biayaDibutuhkan,
                            "kegiatan_acara" => $acara->kegiatanacara,
                            "kota_berlangsung" => $acara->kotaberlangsung,
                            "poster_acara" => $acara->poster_event,
                            "proposal" => $acara->proposal,
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
                    return $this->sendError('Organisation Not Found.', ['error' => 'No Organisation With That ID Was Found'], 404);
                }
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function getHistoryOrganisasi($idOrganisasi): JsonResponse
    {
        try {
            if (Auth::id()) {
                if (UserOrganisasi::where("id_organisasi", $idOrganisasi)->count() > 0) {
                    $dataAcara = Acara::whereDate("tanggalacara", "<", now())->where("id_organisasi", $idOrganisasi)->map(function ($acara) {
                        $userOrganisasi = $acara->organisasis;
                        $biayaDikumpulkan = PembayaranPerusahaan::where('id_acara', $acara->id_acara)->count() > 0 ? array_sum(...PembayaranPerusahaan::where('id_acara', $acara->id_acara)->get()->map(function ($biaya) {
                            return $biaya->biayatotal;
                        })) : 0;
                        $perusahaanKerjasama = PembayaranPerusahaan::where('id_acara', $acara->id_acara)->count() > 0 ? PembayaranPerusahaan::where('id_acara', $acara->id_acara)->groupBy("id_rekeningperusahaan")->get()->map(function ($pembayaran) {
                            $perusahaan = $pembayaran->rekeningperusahaans->perusahaans;
                            return [
                                "namaPerusahaan" => $perusahaan->namaperusahaan,
                                "jumlahSponsor" => array_sum(...PembayaranPerusahaan::where('id_acara', $pembayaran->id_acara)->where("id_rekeningperusahaan", $pembayaran->rekeningperusahaans->id_rekeningperusahaan)->get()->map(function ($biaya) {
                                    return $biaya->biayatotal;
                                }))
                            ];
                        }) : [];
                        return [
                            "id_acara" => $acara->id_acara,
                            "nama_acara" => $acara->namaacara,
                            "tanggal_acara" => $acara->tanggalacara,
                            "lokasi_acara" => $acara->lokasiacara,
                            "biaya_dibutuhkan" => $acara->biayadibutuhkan,
                            "biaya_dikumpulkan" => $biayaDikumpulkan,
                            "kegiatan_acara" => $acara->kegiatanacara,
                            "kota_berlangsung" => $acara->kotaberlangsung,
                            "poster_acara" => $acara->poster_event,
                            "proposal" => $acara->proposal,
                            "organisasi" => [
                                "id_organisasi" => $userOrganisasi->id_organisasi,
                                "nama_organisasi" => $userOrganisasi->namaorganisasi,
                                "kota_domisili_organisasi" => $userOrganisasi->kotadomisiliorganisasi,
                                "nomor_telepon_organisasi" => $userOrganisasi->nomorteleponorganisasi
                            ],
                            "perusahaan_kerjasama" => $perusahaanKerjasama
                        ];
                    });

                    return $this->sendResponse($dataAcara, 'Event history retrieved successfully.');
                } else {
                    return $this->sendError('Organisation Not Found.', ['error' => 'No Organisation With That ID Was Found'], 404);
                }
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function getHistoryPerusahaan($idPerusahaan): JsonResponse
    {
        try {
            if (Auth::id()) {
                if (UserPerusahaan::where("id_perusahaan", $idPerusahaan)->count() > 0) {
                    $dataAcara = PembayaranPerusahaan::where("id_rekeningperusahaan", UserPerusahaan::where("id_user", Auth::user()->id)->rekeningperusahaans->id_rekeningperusahaan)->groupBy("id_acara")->map(function ($pembayaran) {
                        $acara = $pembayaran->acaras;
                        $userOrganisasi = $acara->organisasis;
                        $biayaDikumpulkan = array_sum(...PembayaranPerusahaan::where('id_acara', $acara->id_acara)->get()->map(function ($biaya) {
                            return $biaya->biayatotal;
                        }));
                        $perusahaanKerjasama = PembayaranPerusahaan::where('id_acara', $acara->id_acara)->count() > 0 ? PembayaranPerusahaan::where('id_acara', $acara->id_acara)->groupBy("id_rekeningperusahaan")->get()->map(function ($pembayaran) {
                            $perusahaan = $pembayaran->rekeningperusahaans->perusahaans;
                            return [
                                "namaPerusahaan" => $perusahaan->namaperusahaan,
                                "jumlahSponsor" => array_sum(...PembayaranPerusahaan::where('id_acara', $pembayaran->id_acara)->where("id_rekeningperusahaan", $pembayaran->rekeningperusahaans->id_rekeningperusahaan)->get()->map(function ($biaya) {
                                    return $biaya->biayatotal;
                                }))
                            ];
                        }) : [];
                        return [
                            "id_acara" => $acara->id_acara,
                            "nama_acara" => $acara->namaacara,
                            "tanggal_acara" => $acara->tanggalacara,
                            "lokasi_acara" => $acara->lokasiacara,
                            "biaya_dibutuhkan" => $acara->biayadibutuhkan,
                            "biaya_dikumpulkan" => $biayaDikumpulkan,
                            "kegiatan_acara" => $acara->kegiatanacara,
                            "kota_berlangsung" => $acara->kotaberlangsung,
                            "poster_acara" => $acara->poster_event,
                            "proposal" => $acara->proposal,
                            "organisasi" => [
                                "id_organisasi" => $userOrganisasi->id_organisasi,
                                "nama_organisasi" => $userOrganisasi->namaorganisasi,
                                "kota_domisili_organisasi" => $userOrganisasi->kotadomisiliorganisasi,
                                "nomor_telepon_organisasi" => $userOrganisasi->nomorteleponorganisasi
                            ],
                            "perusahaan_kerjasama" => $perusahaanKerjasama
                        ];
                    });

                    return $this->sendResponse($dataAcara, 'Event history retrieved successfully.');
                } else {
                    return $this->sendError('Company Not Found.', ['error' => 'No Company With That ID Was Found'], 404);
                }
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }
}
