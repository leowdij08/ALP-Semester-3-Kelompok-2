<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\RekeningTemu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Exception;

class RekeningTemuController extends BaseController
{
    public function getRekening(): JsonResponse
    {
        try {
            if (Auth::id()) {
                $rekeningData = RekeningTemu::first()
                    ->map(function ($item) {
                        return [
                            'nomorrekeningtemu' => $item->nomorrekeningtemu,
                            'namabanktemu' => $item->namabanktemu,
                            'pemilikrekeningtemu' => $item->pemilikrekeningtemu,
                        ];
                    });

                return $this->sendResponse($rekeningData, 'RekeningTemu retrieved successfully.');
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
                    $validator = Validator::make($request->all(), [
                        'nomorrekeningtemu' => 'required',
                        'namabanktemu' => 'required|in:BCA,BCA Digital,SEABANK,Mandiri,BNI,DBS',
                        'pemilikrekeningtemu' => 'required',
                    ]);

                    if ($validator->fails()) {
                        return $this->sendError('Validation Error.', $validator->errors(), 400);
                    }

                    $rekeningTemu = RekeningTemu::first()->update([...$request->all(), "updated_at" => now()]);

                    return $this->sendResponse($rekeningTemu, 'RekeningTemu updated successfully.');
            } else {
                return $this->sendError('Unauthorized.', ['error' => 'Access denied. Please log in to update organization accounts.'], 401);
            }
        } catch (Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

}
