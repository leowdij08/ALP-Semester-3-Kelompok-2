<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\UserOrganisasi;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

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

            
                return $this->sendResponse($userData, 'UserOrganisasi data retrieved successfully.');
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

                return $this->sendResponse($dataUser, 'UserOrganisasi searched successfully.');
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
                $idOrganisasi = UserOrganisasi::where('id_user', Auth::user()->id)->first()->id_organisasi;
                if (UserOrganisasi::where("id_organisasi", $idOrganisasi)->count() > 0) {
                    if (UserOrganisasi::where("id_organisasi", $idOrganisasi)->first()->id_user == Auth::user()->id) {
                        $validator = Validator::make($request->all(), [
                            'namaorganisasi' => 'required',
                            'kotadomisiliorgansiasi' => 'required|in:Makassar,Jakarta,Surabaya',
                            'nomorteleponorganisasi' => 'required|regex:/[0-9]/',
                        ]);

                        if ($validator->fails()) {
                            return $this->sendError('Validation Error.', $validator->errors(), 400);
                        }

                        $input = $request->all();
                        $data = [
                            "namaorganisasi" => $input['namaorganisasi'],
                            "kotadomisiliorgansiasi" => $input['kotadomisiliorgansiasi'],
                            "nomorteleponorganisasi" => $input['nomorteleponorganisasi'],
                        ];
                        UserOrganisasi::where('id_organisasi', $idOrganisasi)->update($data);
                        $dataOrganisasi = UserOrganisasi::where("id_organisasi", $idOrganisasi)->get()->map(function ($Organisasi) {
                            return [
                            'id_user' => $Organisasi->id_organisasi,
                            'namaorganisasi' => $Organisasi->namaorganisasi,
                            'kotadomisiliorganisasi' => $Organisasi->kotadomisiliorganisasi,
                            'nomorteleponorganisasi' => $Organisasi->nomorteleponorganisasi,
                            ];
                        });

                        return $this->sendResponse($dataOrganisasi, 'UserOrganisasi updated successfully.');
                    } else {
                        return $this->sendError('Forbidden.', ['error' => 'Not Your Account'], 403);
                    }
                } else {
                    return $this->sendError('Account Not Found.', ['error' => 'No Account With That ID Was Found'], 404);
                }
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

}