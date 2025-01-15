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
    public function getByOrganisasi(): JsonResponse
    {
        try {
            if (Auth::id()) {
                $rekeningData = RekeningOrganisasi::where('id_organisasi', Auth::user()->organisasi)
                    ->first()
                    ->map(function ($item) {
                        return [
                            'id_organisasi' => $item->id_organisasi,
                            'nomorrekeningorganisasi' => $item->nomorrekeningorganisasi,
                            'namabankorganisasi' => $item->namabankorganisasi,
                            'pemilikrekeningorganisasi' => $item->pemilikrekeningorganisasi,
                        ];
                    });

                return $this->sendResponse($rekeningData, 'RekeningOrganisasi retrieved successfully.');
            } else {
                return $this->sendError('Unauthorized.', ['error' => 'Access denied. Please log in to view organization accounts.'], 401);
            }
        } catch (Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                if (RekeningOrganisasi::where('id_organisasi', Auth::user()->organisasi)->count > 0) {
                    $validator = Validator::make($request->all(), [
                        'nomorrekeningorganisasi' => 'required',
                        'namabankorganisasi' => 'required|in:BCA,BCA Digital,SEABANK,Mandiri,BNI,DBS',
                        'pemilikrekeningorganisasi' => 'required',
                    ]);

                    if ($validator->fails()) {
                        return $this->sendError('Validation Error.', $validator->errors(), 400);
                    }

                    $rekeningOrganisasi = RekeningOrganisasi::where('id_organisasi', Auth::user()->organisasi)->update([...$request->all(), "updated_at" => now()]);

                    return $this->sendResponse($rekeningOrganisasi, 'RekeningOrganisasi updated successfully.');
                } else {
                    return $this->sendError('Not Found.', ['error' => 'This account does not have any rekening.'], 404);
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
                if (RekeningOrganisasi::where('id_organisasi', Auth::user()->organisasi)->count == 0) {
                    $validator = Validator::make($request->all(), [
                        'nomorrekeningorganisasi' => 'required',
                        'namabankorganisasi' => 'required|in:BCA,BCA Digital,SEABANK,Mandiri,BNI,DBS',
                        'pemilikrekeningorganisasi' => 'required',
                    ]);

                    if ($validator->fails()) {
                        return $this->sendError('Validation Error.', $validator->errors(), 400);
                    }

                    $rekeningOrganisasi = RekeningOrganisasi::create([...$request->all(), "id_organisasi" => Auth::user()->organisasi, "created_at" => now(), "updated_at" => now()]);

                    return $this->sendResponse($rekeningOrganisasi, 'RekeningOrganisasi created successfully.');
                } else {
                    return $this->sendError('Bad Request.', ['error' => 'This account already have rekening.'], 400);
                }
            } else {
                return $this->sendError('Unauthorized.', ['error' => 'Access denied. Please log in to update organization accounts.'], 401);
            }
        } catch (Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function delete(): JsonResponse
    {
        try {
            if (Auth::id()) {
                if (RekeningOrganisasi::where('id_organisasi', Auth::user()->organisasi)->count > 0) {
                    return $this->sendResponse(['isDeleted' => RekeningOrganisasi::where('id_organisasi', Auth::user()->organisasi)->delete()], 'RekeningOrganisasi deleted successfully.');
                } else {
                    return $this->sendError('Not Found.', ['error' => 'This account does not have any rekening.'], 404);
                }
            } else {
                return $this->sendError('Unauthorized.', ['error' => 'Access denied. Please log in to update organization accounts.'], 401);
            }
        } catch (Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

}
