<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\PenanggungJawabOrganisasi;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenanggungJawabOrganisasiController extends BaseController
{
    public function getbyID($id, Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                $userData = PenanggungJawabOrganisasi::where('id_organisasi', $id)
                    ->get()->map(
                        function ($item) {
                            return [
                                'id_user' => $item->id_organisasi,
                                'namalengkappjo' => $item->namalengkappjo,
                                'tanggallahirpjo' => $item->tanggallahirpjo,
                                'emailpjo' => $item->emailpjo,
                                'alamatlengkappjo' => $item->alamatlengkappjo,
                                'ktppjo' => $item->ktppjo,
                            ];
                        }
                    );


                return $this->sendResponse($userData, 'PenanggungJawabOrganisasi data retrieved successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage());
        }
    }

    public function search(Request $request, $keyword): JsonResponse
    {
        try {
            if (Auth::id()) {
                $filters = $request->all();
                $dataUser = PenanggungJawabOrganisasi::whereRaw("concat(namalengkappjo, emailpjo) like ?", ["%$keyword%"])
                    ->get()->map(function ($item) {
                        return [
                            'id_user' => $item->id_organisasi,
                            'namalengkappjo' => $item->namalengkappjo,
                            'tanggallahirpjo' => $item->tanggallahirpjo,
                            'emailpjo' => $item->emailpjo,
                            'alamatlengkappjo' => $item->alamatlengkappjo,
                            'ktppjo' => $item->ktppjo,
                        ];
                    });

                return $this->sendResponse($dataUser, 'PenanggungJawabOrganisasi searched successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage());
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                $userOrganisasi = UserOrganisasi::where('id_user', Auth::user()->id)->first();
                $penanggungJawab = $userOrganisasi->penanggungjawab;
                $validator = Validator::make($request->all(), [
                    'namaLengkapPenanggungJawab' => 'required',
                    'tanggalLahirPenanggungJawab' => 'required|date|date_format:Y-m-d|before:today',
                    'alamatLengkapPenanggungJawab' => 'required',
                    'emailPenanggungJawab' => 'required|email',
                ]);

                if ($validator->fails()) {
                    return $this->sendError('Validation Error.', $validator->errors(), 400);
                }

                $input = $request->all();
                $data = [
                    "namalengkappjo" => $input['namaLengkapPenanggungJawab'],
                    "tanggallahirpjo" => $input['tanggalLahirPenanggungJawab'],
                    "alamatlengkappjo" => $input['alamatLengkapPenanggungJawab'],
                    "emailpjo" => $input['emailPenanggungJawab'],
                ];
                if (isset($input['ktp'])) $data['ktppjo'] = $input['ktp'];
                $penanggungJawab->update($data);
                $dataPJO = $penanggungJawab->get()->map(function ($item) {
                    return [
                        'id_user' => $item->id_organisasi,
                        'namalengkappjo' => $item->namalengkappjo,
                        'tanggallahirpjo' => $item->tanggallahirpjo,
                        'emailpjo' => $item->emailpjo,
                        'alamatlengkappjo' => $item->alamatlengkappjo,
                        'ktppjo' => $item->ktppjo,
                    ];
                });

                return $this->sendResponse($dataPJO, 'Penanggung Jawab updated successfully.');
            } else {
                return $this->sendError('Forbidden.', ['error' => 'Not Your Account'], 403);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }
}
