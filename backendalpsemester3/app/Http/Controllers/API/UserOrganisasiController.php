<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\UserOrganisasi;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserOrganisasiController extends BaseController
{
    public function getbyID($id, Request $request ): JsonResponse
    {
        try {
            if (Auth::id()) {
                $userData = UserOrganisasi::
                    where('id_organisasi',$id)
                    ->get()->map(
                        function ($item) {
                            return [
                                'id_user' => $item->id_organisasi,
                                'namaorganisasi' => $item->namaorganisasi,
                                'kotadomisiliorganisasi' => $item->kotadomisiliorganisasi,
                                'nomorteleponorganisasi' => $item->nomorteleponorganisasi,
                            ];
                        }
                    );

            
                return $this->sendResponse($userData, 'User data retrieved successfully.');
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
                $dataUser = UserOrganisasi::whereRaw("concat(namaorganisasi, kotadomisiliorganisasi) like ?", ["%$keyword%"])
                ->get()->map(function ($item) {
                    return [
                        'id_user' => $item->id_organisasi,
                        'namaorganisasi' => $item->namaorganisasi,
                        'kotadomisiliorganisasi' => $item->kotadomisiliorganisasi,
                        'nomorteleponorganisasi' => $item->nomorteleponorganisasi,
                    ];
                });

                return $this->sendResponse($dataUser, 'Events searched successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage());
        }
}

}