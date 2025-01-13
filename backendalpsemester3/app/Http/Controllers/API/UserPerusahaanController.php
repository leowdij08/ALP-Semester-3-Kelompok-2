<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\UserPerusahaan;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

class UserPerusahaanController extends BaseController
{
    public function getbyID($id): JsonResponse
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
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
}

}
