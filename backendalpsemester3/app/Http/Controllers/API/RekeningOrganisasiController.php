<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\RekeningOrganisasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Exception;

class RekeningOrganisasiController extends BaseController
{
    public function getById($id): JsonResponse
    {
        try {
            if (Auth::id()) {
                $userData = RekeningOrganisasi::where('id_rekeningorganisasi', $id)
                    ->get()
                    ->map(function ($item) {
                        return [
                            'id_organisasi' => $item->id_organisasi,
                            'nomorrekeningorganisasi' => $item->nomorrekeningorganisasi,
                            'namabankorganisasi' => $item->namabankorganisasi,
                            'pemilikrekeningorganisasi' => $item->pemilikrekeningorganisasi,
                            'isActive' => $item->isActive,
                        ];
                    });

                return $this->sendResponse($userData, 'RekeningOrganisasi retrieved successfully.');
            } else {
                return $this->sendError('Unauthorized.', ['error' => 'Access denied. Please log in to view organization accounts.'], 401);
            }
        } catch (Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function search($keyword): JsonResponse
    {
        try {
            if (Auth::id()) {
                $dataUser = RekeningOrganisasi::where('nomorrekeningorganisasi', 'like', "%$keyword%")
                    ->get()
                    ->map(function ($item) {
                        return [
                            'id_organisasi' => $item->id_organisasi,
                            'nomorrekeningorganisasi' => $item->nomorrekeningorganisasi,
                            'namabankorganisasi' => $item->namabankorganisasi,
                            'pemilikrekeningorganisasi' => $item->pemilikrekeningorganisasi,
                            'isActive' => $item->isActive,
                        ];
                    });

                return $this->sendResponse($dataUser, 'Search results retrieved successfully.');
            } else {
                return $this->sendError('Unauthorized.', ['error' => 'Access denied. Please log in to search organization accounts.'], 401);
            }
        } catch (Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function update($idRekeningOrganisasi, Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                $rekeningOrganisasi = RekeningOrganisasi::find($idRekeningOrganisasi);

                if ($rekeningOrganisasi && $rekeningOrganisasi->id_organisasi == Auth::user()->id) {
                    $validator = Validator::make($request->all(), [
                        'nomorrekeningorganisasi' => 'required',
                        'namabankorganisasi' => 'required|in:BCA,BCA Digital,SEABANK,Mandiri,BNI,DBS',
                        'pemilikrekeningorganisasi' => 'required',
                        'isActive' => 'boolean',
                    ]);

                    if ($validator->fails()) {
                        return $this->sendError('Validation Error.', $validator->errors(), 400);
                    }

                    $rekeningOrganisasi->update($request->all());

                    return $this->sendResponse($rekeningOrganisasi, 'RekeningOrganisasi updated successfully.');
                } else {
                    return $this->sendError('Forbidden.', ['error' => 'You do not have permission to update this account.'], 403);
                }
            } else {
                return $this->sendError('Unauthorized.', ['error' => 'Access denied. Please log in to update organization accounts.'], 401);
            }
        } catch (Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function create(Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                $validator = Validator::make($request->all(), [
                    'id_organisasi' => 'required|exists:user_organisasi,id_organisasi',
                    'nomorrekeningorganisasi' => 'required',
                    'namabankorganisasi' => 'required|in:BCA,BCA Digital,SEABANK,Mandiri,BNI,DBS',
                    'pemilikrekeningorganisasi' => 'required',
                    'isActive' => 'boolean',
                ]);

                if ($validator->fails()) {
                    return $this->sendError('Validation Error.', $validator->errors(), 400);
                }

                $rekeningOrganisasi = RekeningOrganisasi::create($request->all());

                return $this->sendResponse($rekeningOrganisasi, 'RekeningOrganisasi created successfully.');
            } else {
                return $this->sendError('Unauthorized.', ['error' => 'Access denied. Please log in to create organization accounts.'], 401);
            }
        } catch (Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function delete($idRekeningOrganisasi): JsonResponse
    {
        try {
            if (Auth::id()) {
                $rekeningOrganisasi = RekeningOrganisasi::find($idRekeningOrganisasi);

                if ($rekeningOrganisasi && $rekeningOrganisasi->id_organisasi == Auth::user()->id) {
                    $rekeningOrganisasi->delete();

                    return $this->sendResponse(['isDeleted' => true], 'RekeningOrganisasi deleted successfully.');
                } else {
                    return $this->sendError('Forbidden.', ['error' => 'You do not have permission to delete this account.'], 403);
                }
            } else {
                return $this->sendError('Unauthorized.', ['error' => 'Access denied. Please log in to delete organization accounts.'], 401);
            }
        } catch (Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }
}
