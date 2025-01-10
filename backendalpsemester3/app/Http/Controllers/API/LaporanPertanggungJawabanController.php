<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\UserPerusahaan;
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
                $userData = LaporanPertanggungjawaban::
                    where('id_perusahaan',$id)
                    ->get()->map(
                        function ($item) {
                            return [
                                'id_user' => $item->id_perusahaan,
                                'id_acara' => $item->id_acara,
                                'dokumentasilpj' => $item->dokumentasilpj,
                                'diterima' => $item->diterima,
                                'revisike' => $item->revisike,
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
                $dataUser = LaporanPertanggungjawaban::whereRaw("concat(namaperusahaan, kotadomisiliperusahaan) like ?", ["%$keyword%"])
                ->get()->map(function ($item) {
                    return [
                        'id_user' => $item->id_perusahaan,
                        'id_acara' => $item->id_acara,
                        'dokumentasilpj' => $item->dokumentasilpj,
                        'diterima' => $item->diterima,
                        'revisike' => $item->revisike,
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
