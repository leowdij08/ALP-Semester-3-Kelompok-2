<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\PenanggungJawabPerusahaan;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenanggungJawabPerusahaanController extends BaseController
{
    public function getbyID($id, Request $request ): JsonResponse
    {
        try {
            if (Auth::id()) {
                $userData = PenanggungJawabPerusahaan::
                    where('id_organisasi',$id)
                    ->get()->map(
                        function ($item) {
                            return [
                                'id_perusahaan' => $item->id_perusahaan,
                                'namalengkappjp' => $item->namalengkappjp,
                                'tanggallahirpjp' => $item->tanggallahirpjp,
                                'emailpjp' => $item->emailpjp,
                                'alamatlengkappjp' => $item->alamatlengkappjp,
                                'ktppjp' => $item->ktppjp,
                            ];
                        }
                    );


                return $this->sendResponse($userData, 'PenanggungJawab data retrieved successfully.');
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
                $dataUser = PenanggungJawabPerusahaan::whereRaw("concat(namalengkappjp, emailpjp) like ?", ["%$keyword%"])
                ->get()->map(function ($item) {
                    return [
                        'id_perusahaan' => $item->id_perusahaan,
                        'namalengkappjp' => $item->namalengkappjp,
                        'tanggallahirpjp' => $item->tanggallahirpjp,
                        'emailpjp' => $item->emailpjp,
                        'alamatlengkappjp' => $item->alamatlengkappjp,
                        'ktppjp' => $item->ktppjp,
                    ];
                });

                return $this->sendResponse($dataUser, 'PenanggungJawab searched successfully.');
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
                "namalengkappjp" => $input['namaLengkapPenanggungJawab'],
                "tanggallahirpjp" => $input['tanggalLahirPenanggungJawab'],
                "alamatlengkappjp" => $input['alamatLengkapPenanggungJawab'],
                "emailpjp" => $input['emailPenanggungJawab'],
            ];
            if (isset($input['ktp'])) $data['ktppjp'] = $input['ktp'];
            $penanggungJawab->update($data);
            $dataPJP = $penanggungJawab->get()->map(function ($item) {
                return [
                    'id_user' => $item->id_organisasi,
                    'namalengkappjp' => $item->namalengkappjp,
                    'tanggallahirpjp' => $item->tanggallahirpjp,
                    'emailpjp' => $item->emailpjp,
                    'alamatlengkappjp' => $item->alamatlengkappjp,
                    'ktppjp' => $item->ktppjp,
                ];
            });

            return $this->sendResponse($dataPJP, 'Penanggung Jawab updated successfully.');
        } else {
            return $this->sendError('Forbidden.', ['error' => 'Not Your Account'], 403);
        }
    } catch (\Exception $e) {
        return $this->sendError('Server Error.', $e->getMessage(), 500);
    }
}
}
