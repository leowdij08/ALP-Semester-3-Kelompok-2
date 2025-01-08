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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AcaraController extends BaseController
{
    public function getAll(Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                $dataAcara = Acara::all()->map(function ($acara) {
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

                return $this->sendResponse($dataAcara, 'Events retrieved successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage());
        }
    }

    public function filter(Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                $filters = $request->all();
                $dataAcara = Acara::
                where("biayadibutuhkan", (isset($filters["minHarga"]) ? ">=" : "!="), (isset($filters["minHarga"]) ? $filters["minHarga"] : ""))
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

                return $this->sendResponse($dataAcara, 'Events retrieved successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage());
        }
    }
}
