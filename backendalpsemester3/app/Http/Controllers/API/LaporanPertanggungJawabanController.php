<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\UserPerusahaan;
use App\Models\Acara;
use App\Models\LaporanPertanggungjawaban;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LaporanPertanggungjawabanController extends BaseController
{
    public function getbyID($id): JsonResponse
    {
        try {
            if (Auth::id()) {
                $userData = LaporanPertanggungjawaban::where('id_laporan', $id)
                    ->get()->map(
                        function ($item) {
                            return [
                                'id_acara' => $item->id_acara,
                                'dokumentasilpj' => $item->dokumentasilpj,
                                'diterima' => $item->diterima,
                                'revisike' => $item->revisike,
                            ];
                        }
                    );


                return $this->sendResponse($userData, 'Report data retrieved successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function search($keyword): JsonResponse
    {
        try {
            if (Auth::id()) {
                $dataUser = LaporanPertanggungjawaban::whereRaw("concat(id_acara, revisike) like ?", ["%$keyword%"])
                    ->get()->map(function ($item) {
                        return [
                            'id_acara' => $item->id_acara,
                            'dokumentasilpj' => $item->dokumentasilpj,
                            'diterima' => $item->diterima,
                            'revisike' => $item->revisike,
                        ];
                    });

                return $this->sendResponse($dataUser, 'Report searched successfully.');
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
                    $acara = Acara::where("id_acara", $idAcara)->first();
                    if ($acara->id_organisasi == Auth::user()->id) {
                        $validator = Validator::make($request->all(), [
                            'dokumentasiLPJ' => 'required',
                        ]);

                        if ($validator->fails()) {
                            return $this->sendError('Validation Error.', $validator->errors(), 400);
                        }

                        $input = $request->all();
                        $laporan = LaporanPertanggungjawaban::where('id_acara', $idAcara);
                        $data = [
                            "dokumentasilpj" => $input['dokumentasiLPJ'],
                            "revisike" => $laporan->revisike + 1
                        ];
                        $laporan->update($data);
                        $dataLaporan = $laporan->get()->map(function ($item) {
                            return [
                                'id_acara' => $item->id_acara,
                                'dokumentasilpj' => $item->dokumentasilpj,
                                'diterima' => $item->diterima,
                                'revisike' => $item->revisike,
                            ];
                        });

                        return $this->sendResponse($dataLaporan, 'Report updated successfully.');
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

    public function create($idAcara, Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                if (Acara::where("id_acara", $idAcara)->count() > 0) {
                    $acara = Acara::where("id_acara", $idAcara)->first();
                    if ($acara->id_organisasi == Auth::user()->id) {
                        $validator = Validator::make($request->all(), [
                            'dokumentasiLPJ' => 'required',
                        ]);

                        if ($validator->fails()) {
                            return $this->sendError('Validation Error.', $validator->errors(), 400);
                        }

                        $input = $request->all();
                        $data = [
                            "dokumentasilpj" => $input['dokumentasiLPJ'],
                            "revisike" => 1
                        ];
                        $laporan = LaporanPertanggungjawaban::create($data);
                        $dataLaporan = $laporan->get()->map(function ($item) {
                            return [
                                'id_acara' => $item->id_acara,
                                'dokumentasilpj' => $item->dokumentasilpj,
                                'diterima' => $item->diterima,
                                'revisike' => $item->revisike,
                            ];
                        });

                        return $this->sendResponse($dataLaporan, 'Report updated successfully.');
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

    public function delete($idAcara, Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                if (Acara::where("id_acara", $idAcara)->count() > 0) {
                    if (Acara::where("id_acara", $idAcara)->first()->id_organisasi == Auth::user()->id) {
                        return $this->sendResponse(['isDeleted' => LaporanPertanggungjawaban::where('id_acara', $idAcara)->delete() ? true : false], 'Report deleted successfully.');
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
}
