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

public function update(Request $request): JsonResponse
{
    try {
        if (Auth::id()) {
            $userPerusahaan = UserPerusahaan::where('id_user', Auth::user()->id)->first();
            $validator = Validator::make($request->all(), [
                'namaperusahaan' => 'required',
                'kotadomisiliperusahaan' => 'required|in:Makassar,Jakarta,Surabaya',
                'nomorteleponperusahaan' => 'required|regex:/[0-9]/',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 400);
            }

            $input = $request->all();
            $data = [
                "namaperusahaan" => $input['namaperusahaan'],
                "kotadomisiliperusahaan" => $input['kotadomisiliperusahaan'],
                "nomorteleponperusahaan" => $input['nomorteleponperusahaan'],
            ];
            $userPerusahaan->update($data);
            $dataPerusahaan = $userPerusahaan->get()->map(function ($Perusahaan) {
                return [
                    'id_user' => $Perusahaan->id_perusahaan,
                    'namaperusahaan' => $Perusahaan->namaperusahaan,
                    'kotadomisiliperusahaan' => $Perusahaan->kotadomisiliperusahaan,
                    'nomorteleponperusahaan' => $Perusahaan->nomorteleponperusahaan,
                ];
            });

            return $this->sendResponse($dataPerusahaan, 'UserPerusahaan updated successfully.');
        } else {
            return $this->sendError('Forbidden.', ['error' => 'Not Your Account'], 403);
        }
    } catch (\Exception $e) {
        return $this->sendError('Server Error.', $e->getMessage(), 500);
    }
}
}
