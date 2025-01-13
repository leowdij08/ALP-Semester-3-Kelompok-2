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
    public function getbyID($id, Request $request ): JsonResponse
    {
        try {
            if (Auth::id()) {
                $userData = PenanggungJawabOrganisasi::
                    where('id_organisasi',$id)
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

}