<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\RekeningOrganisasi;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RekeningOrganisasiController extends BaseController
{
    public function getbyID($id): JsonResponse
    {
        try {
            if (Auth::id()) {
                $userData = RekeningOrganisasi::where('id_rekeningorganisasi', $id)
                    ->get()->map(
                        function ($item) {
                            return [
                                'id_organisasi' => $item->id_organisasi,
                                'nomorrekeningorganisasi' => $item->nomorrekeningorganisasi,
                                'namabankorganisasi' => $item->namabankorganisasi,
                                'pemilikrekeningorganisasi' => $item->pemilikrekeningorganisasi,
                            ];
                        }
                    );

                return $this->sendResponse($userData, 'RekeningOrganisasi retrieved successfully.');
            } else {
                return $this->sendError('Unauthorized Access.', ['error' => 'You are not authorized to access this RekeningOrganisasi.'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', ['error' => $e->getMessage()], 500);
        }
    }

    public function search($keyword): JsonResponse
    {
        try {
            if (Auth::id()) {
                $dataUser = RekeningOrganisasi::whereRaw("concat(nomorrekeningorganisasi) like ?", ["%$keyword%"])
                    ->get()->map(function ($item) {
                        return [
                            'id_organisasi' => $item->id_organisasi,
                            'nomorrekeningorganisasi' => $item->nomorrekeningorganisasi,
                            'namabankorganisasi' => $item->namabankorganisasi,
                            'pemilikrekeningorganisasi' => $item->pemilikrekeningorganisasi,
                        ];
                    });

                return $this->sendResponse($dataUser, 'Search completed successfully.');
            } else {
                return $this->sendError('Unauthorized Access.', ['error' => 'You are not authorized to perform this search.'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', ['error' => $e->getMessage()], 500);
        }
    }

    public function update($idRekeningOrganisasi, Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                if (RekeningOrganisasi::where("id_rekeningorganisasi", $idRekeningOrganisasi)->exists()) {
                    $RekeningOrganisasi = RekeningOrganisasi::where("id_rekeningorganisasi", $idRekeningOrganisasi)->first();
                    if ($RekeningOrganisasi->id_organisasi == Auth::user()->id) {
                        $validator = Validator::make($request->all(), [
                            'nomorrekeningorganisasi' => 'required',
                            'namabankorganisasi' => 'required|in:BCA,BCA DIGITAL,Mandiri,BNI,DBS',
                            'pemilikrekeningorganisasi' => 'required',
                        ]);

                        if ($validator->fails()) {
                            return $this->sendError('Validation Error.', $validator->errors(), 400);
                        }

                        $input = $request->all();
                        $data = [
                            "nomorrekeningorganisasi" => $input['nomorrekeningorganisasi'],
                            "namabankorganisasi" => $input['namabankorganisasi'],
                            "pemilikrekeningorganisasi" => $input['pemilikrekeningorganisasi'],
                        ];

                        $RekeningOrganisasi->update($data);

                        return $this->sendResponse($RekeningOrganisasi, 'RekeningOrganisasi updated successfully.');
                    } else {
                        return $this->sendError('Forbidden Access.', ['error' => 'You do not have permission to update this RekeningOrganisasi.'], 403);
                    }
                } else {
                    return $this->sendError('Not Found.', ['error' => 'RekeningOrganisasi with the given ID was not found.'], 404);
                }
            } else {
                return $this->sendError('Unauthorized Access.', ['error' => 'You are not authorized to update this RekeningOrganisasi.'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', ['error' => $e->getMessage()], 500);
        }
    }

    public function create(Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                $validator = Validator::make($request->all(), [
                    'nomorrekeningorganisasi' => 'required',
                    'namabankorganisasi' => 'required|in:BCA,BCA DIGITAL,Mandiri,BNI,DBS',
                    'pemilikrekeningorganisasi' => 'required',
                ]);

                if ($validator->fails()) {
                    return $this->sendError('Validation Error.', $validator->errors(), 400);
                }

                $input = $request->all();
                $RekeningOrganisasi = RekeningOrganisasi::create($input);

                return $this->sendResponse($RekeningOrganisasi, 'RekeningOrganisasi created successfully.');
            } else {
                return $this->sendError('Unauthorized Access.', ['error' => 'You are not authorized to create this RekeningOrganisasi.'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', ['error' => $e->getMessage()], 500);
        }
    }

    public function delete($idRekeningOrganisasi): JsonResponse
    {
        try {
            if (Auth::id()) {
                if (RekeningOrganisasi::where("id_rekeningorganisasi", $idRekeningOrganisasi)->exists()) {
                    $RekeningOrganisasi = RekeningOrganisasi::where("id_rekeningorganisasi", $idRekeningOrganisasi)->first();
                    if ($RekeningOrganisasi->id_organisasi == Auth::user()->id) {
                        $RekeningOrganisasi->delete();
                        return $this->sendResponse(['isDeleted' => true], 'RekeningOrganisasi deleted successfully.');
                    } else {
                        return $this->sendError('Forbidden Access.', ['error' => 'You do not have permission to delete this RekeningOrganisasi.'], 403);
                    }
                } else {
                    return $this->sendError('Not Found.', ['error' => 'RekeningOrganisasi with the given ID was not found.'], 404);
                }
            } else {
                return $this->sendError('Unauthorized Access.', ['error' => 'You are not authorized to delete this RekeningOrganisasi.'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', ['error' => $e->getMessage()], 500);
        }
    }
}
