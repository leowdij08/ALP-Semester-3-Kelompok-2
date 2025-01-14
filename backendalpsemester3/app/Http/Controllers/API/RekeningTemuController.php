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
    public function getById($id): JsonResponse
    {
        try {
            if (Auth::id()) {
                $rekeningTemu = RekeningTemu::find($id);

                if ($rekeningTemu) {
                    return $this->sendResponse($rekeningTemu, 'RekeningTemu retrieved successfully.');
                } else {
                    return $this->sendError('Not Found.', ['error' => 'RekeningTemu not found.'], 404);
                }
            } else {
                return $this->sendError('Unauthorized.', ['error' => 'Access denied. Please log in to view RekeningTemu.'], 401);
            }
        } catch (Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function search($keyword): JsonResponse
    {
        try {
            if (Auth::id()) {
                $results = RekeningTemu::where('nomorrekeningtemu', 'like', "%$keyword%")
                    ->get();

                return $this->sendResponse($results, 'Search results retrieved successfully.');
            } else {
                return $this->sendError('Unauthorized.', ['error' => 'Access denied. Please log in to search RekeningTemu.'], 401);
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
                    'nomorrekeningtemu' => 'required',
                    'namabanktemu' => 'required|in:SEABANK,BCA,BCA Digital',
                    'pemilikrekeningtemu' => 'required|max:45',
                ]);

                if ($validator->fails()) {
                    return $this->sendError('Validation Error.', $validator->errors(), 400);
                }

                $rekeningTemu = RekeningTemu::create($request->all());

                return $this->sendResponse($rekeningTemu, 'RekeningTemu created successfully.');
            } else {
                return $this->sendError('Unauthorized.', ['error' => 'Access denied. Please log in to create RekeningTemu.'], 401);
            }
        } catch (Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function update($idRekeningTemu, Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                $rekeningTemu = RekeningTemu::find($idRekeningTemu);

                if ($rekeningTemu) {
                    $validator = Validator::make($request->all(), [
                        'nomorrekeningtemu' => 'required',
                        'namabanktemu' => 'required|in:SEABANK,BCA,BCA Digital',
                        'pemilikrekeningtemu' => 'required|max:45',
                    ]);

                    if ($validator->fails()) {
                        return $this->sendError('Validation Error.', $validator->errors(), 400);
                    }

                    $rekeningTemu->update($request->all());

                    return $this->sendResponse($rekeningTemu, 'RekeningTemu updated successfully.');
                } else {
                    return $this->sendError('Not Found.', ['error' => 'RekeningTemu not found.'], 404);
                }
            } else {
                return $this->sendError('Unauthorized.', ['error' => 'Access denied. Please log in to update RekeningTemu.'], 401);
            }
        } catch (Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function delete($idRekeningTemu): JsonResponse
    {
        try {
            if (Auth::id()) {
                $rekeningTemu = RekeningTemu::find($idRekeningTemu);

                if ($rekeningTemu) {
                    $rekeningTemu->delete();

                    return $this->sendResponse(['isDeleted' => true], 'RekeningTemu deleted successfully.');
                } else {
                    return $this->sendError('Not Found.', ['error' => 'RekeningTemu not found.'], 404);
                }
            } else {
                return $this->sendError('Unauthorized.', ['error' => 'Access denied. Please log in to delete RekeningTemu.'], 401);
            }
        } catch (Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }
}
