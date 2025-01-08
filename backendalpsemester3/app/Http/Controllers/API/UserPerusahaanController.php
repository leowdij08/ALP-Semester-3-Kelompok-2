<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Models\UserOrganisasi;
use App\Models\PenanggungJawabOrganisasi;
use App\Models\UserPerusahaan;
use App\Models\PenanggungJawabPerusahaan;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserPerusahaanController extends BaseController
{
    public function getbyID($id, Request $request ): JsonResponse
    {
        try {
            if (Auth::id()) {
                $userData = UserPerusahaan::
                    where('id_perusahaan',$id)
                    ->get()->map(
                        function ($item) {
                            return [
                                'id_user' => $item->id_perusahaan,
                                'namaperusahaan' => $item->namaperusahaan,
                                'kotadomisiliperusahaan' => $item->kotadomisiliperusahaan,
                                'nomorteleponperusahaan' => $item->nomorteleponperusahaan,
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
                $dataUser = UserPerusahaan::whereRaw("concat(namaperusahaan, kotadomisiliperusahaan) like ?", ["%$keyword%"])
                ->get()->map(function ($item) {
                    return [
                        'id_user' => $item->id_perusahaan,
                        'namaperusahaan' => $item->namaperusahaan,
                        'kotadomisiliperusahaan' => $item->kotadomisiliperusahaan,
                        'nomorteleponperusahaan' => $item->nomorteleponperusahaan,
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