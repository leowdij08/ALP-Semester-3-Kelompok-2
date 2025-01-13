<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\RekeningPerusahaan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Exception;

class RekeningPerusahaanController extends BaseController
{
    public function getById($id): JsonResponse
    {
        try {
            if (Auth::id()) {
                $userData = RekeningPerusahaan::where('id_rekeningperusahaan', $id)
                    ->get()
                    ->map(function ($item) {
                        return [
                            'id_perusahaan' => $item->id_perusahaan,
                            'nomorrekeningperusahaan' => $item->nomorrekeningperusahaan,
                            'namabankperusahaan' => $item->namabankperusahaan,
                            'pemilikrekeningperusahaan' => $item->pemilikrekeningperusahaan,
                            'isActive' => $item->isActive,
                        ];
                    });

                return $this->sendResponse($userData, 'RekeningPerusahaan retrieved successfully.');
            } else {
                return $this->sendError('Unauthorized.', ['error' => 'Access denied. Please log in to view company accounts.'], 401);
            }
        } catch (Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function search($keyword): JsonResponse
    {
        try {
            if (Auth::id()) {
                $dataUser = RekeningPerusahaan::where('nomorrekeningperusahaan', 'like', "%$keyword%")
                    ->get()
                    ->map(function ($item) {
                        return [
                            'id_perusahaan' => $item->id_perusahaan,
                            'nomorrekeningperusahaan' => $item->nomorrekeningperusahaan,
                            'namabankperusahaan' => $item->namabankperusahaan,
                            'pemilikrekeningperusahaan' => $item->pemilikrekeningperusahaan,
                            'isActive' => $item->isActive,
                        ];
                    });

                return $this->sendResponse($dataUser, 'Search results retrieved successfully.');
            } else {
                return $this->sendError('Unauthorized.', ['error' => 'Access denied. Please log in to search company accounts.'], 401);
            }
        } catch (Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function update($idRekeningPerusahaan, Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                $rekeningPerusahaan = RekeningPerusahaan::find($idRekeningPerusahaan);

                if ($rekeningPerusahaan && $rekeningPerusahaan->id_perusahaan == Auth::user()->id) {
                    $validator = Validator::make($request->all(), [
                        'nomorrekeningperusahaan' => 'required',
                        'namabankperusahaan' => 'required|in:BCA,BCA Digital,SEABANK,Mandiri,BNI,DBS',
                        'pemilikrekeningperusahaan' => 'required',
                        'isActive' => 'boolean',
                    ]);

                    if ($validator->fails()) {
                        return $this->sendError('Validation Error.', $validator->errors(), 400);
                    }

                    $rekeningPerusahaan->update($request->all());

                    return $this->sendResponse($rekeningPerusahaan, 'RekeningPerusahaan updated successfully.');
                } else {
                    return $this->sendError('Forbidden.', ['error' => 'You do not have permission to update this account.'], 403);
                }
            } else {
                return $this->sendError('Unauthorized.', ['error' => 'Access denied. Please log in to update company accounts.'], 401);
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
                    'id_perusahaan' => 'required|exists:user_perusahaan,id_perusahaan',
                    'nomorrekeningperusahaan' => 'required',
                    'namabankperusahaan' => 'required|in:BCA,BCA Digital,SEABANK,Mandiri,BNI,DBS',
                    'pemilikrekeningperusahaan' => 'required',
                    'isActive' => 'boolean',
                ]);

                if ($validator->fails()) {
                    return $this->sendError('Validation Error.', $validator->errors(), 400);
                }

                $rekeningPerusahaan = RekeningPerusahaan::create($request->all());

                return $this->sendResponse($rekeningPerusahaan, 'RekeningPerusahaan created successfully.');
            } else {
                return $this->sendError('Unauthorized.', ['error' => 'Access denied. Please log in to create company accounts.'], 401);
            }
        } catch (Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function delete($idRekeningPerusahaan): JsonResponse
    {
        try {
            if (Auth::id()) {
                $rekeningPerusahaan = RekeningPerusahaan::find($idRekeningPerusahaan);

                if ($rekeningPerusahaan && $rekeningPerusahaan->id_perusahaan == Auth::user()->id) {
                    $rekeningPerusahaan->delete();

                    return $this->sendResponse(['isDeleted' => true], 'RekeningPerusahaan deleted successfully.');
                } else {
                    return $this->sendError('Forbidden.', ['error' => 'You do not have permission to delete this account.'], 403);
                }
            } else {
                return $this->sendError('Unauthorized.', ['error' => 'Access denied. Please log in to delete company accounts.'], 401);
            }
        } catch (Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }
}
