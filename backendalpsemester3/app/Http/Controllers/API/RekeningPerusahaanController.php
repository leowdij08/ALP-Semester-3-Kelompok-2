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
    public function getByPerusahaan(): JsonResponse
    {
        try {
            if (Auth::id()) {
                $rekeningData = RekeningPerusahaan::where('id_perusahaan', UserPerusahaan::where("id_user", Auth::user()->id))
                    ->first()
                    ->map(function ($item) {
                        return [
                            'id_perusahaan' => $item->id_perusahaan,
                            'nomorrekeningperusahaan' => $item->nomorrekeningperusahaan,
                            'namabankperusahaan' => $item->namabankperusahaan,
                            'pemilikrekeningperusahaan' => $item->pemilikrekeningperusahaan,
                        ];
                    });

                return $this->sendResponse($rekeningData, 'RekeningPerusahaan retrieved successfully.');
            } else {
                return $this->sendError('Unauthorized.', ['error' => 'Access denied. Please log in to view company accounts.'], 401);
            }
        } catch (Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                if (RekeningPerusahaan::where('id_perusahaan', UserPerusahaan::where("id_user", Auth::user()->id))->count > 0) {
                    $validator = Validator::make($request->all(), [
                        'nomorrekeningperusahaan' => 'required',
                        'namabankperusahaan' => 'required|in:BCA,BCA Digital,SEABANK,Mandiri,BNI,DBS',
                        'pemilikrekeningperusahaan' => 'required',
                    ]);

                    if ($validator->fails()) {
                        return $this->sendError('Validation Error.', $validator->errors(), 400);
                    }

                    $rekeningPerusahaan = RekeningPerusahaan::where('id_perusahaan', UserPerusahaan::where("id_user", Auth::user()->id))->update([...$request->all(), "updated_at" => now()]);

                    return $this->sendResponse($rekeningPerusahaan, 'RekeningPerusahaan updated successfully.');
                } else {
                    return $this->sendError('Not Found.', ['error' => 'This account does not have any rekening.'], 404);
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
                if (RekeningPerusahaan::where('id_perusahaan', UserPerusahaan::where("id_user", Auth::user()->id))->count == 0) {
                    $validator = Validator::make($request->all(), [
                        'nomorrekeningperusahaan' => 'required',
                        'namabankperusahaan' => 'required|in:BCA,BCA Digital,SEABANK,Mandiri,BNI,DBS',
                        'pemilikrekeningperusahaan' => 'required',
                    ]);

                    if ($validator->fails()) {
                        return $this->sendError('Validation Error.', $validator->errors(), 400);
                    }

                    $rekeningPerusahaan = RekeningPerusahaan::create([...$request->all(), "id_perusahaan" => UserPerusahaan::where("id_user", Auth::user()->id), "created_at" => now(), "updated_at" => now()]);

                    return $this->sendResponse($rekeningPerusahaan, 'RekeningPerusahaan created successfully.');
                } else {
                    return $this->sendError('Bad Request.', ['error' => 'This account already have rekening.'], 400);
                }
            } else {
                return $this->sendError('Unauthorized.', ['error' => 'Access denied. Please log in to update company accounts.'], 401);
            }
        } catch (Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function delete(): JsonResponse
    {
        try {
            if (Auth::id()) {
                if (RekeningPerusahaan::where('id_perusahaan', UserPerusahaan::where("id_user", Auth::user()->id))->count > 0) {
                    return $this->sendResponse(['isDeleted' => RekeningPerusahaan::where('id_perusahaan', UserPerusahaan::where("id_user", Auth::user()->id))->delete()], 'RekeningPerusahaan deleted successfully.');
                } else {
                    return $this->sendError('Not Found.', ['error' => 'This account does not have any rekening.'], 404);
                }
            } else {
                return $this->sendError('Unauthorized.', ['error' => 'Access denied. Please log in to update company accounts.'], 401);
            }
        } catch (Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

}
