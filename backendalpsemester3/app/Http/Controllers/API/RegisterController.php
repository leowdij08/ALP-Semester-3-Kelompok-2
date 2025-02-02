<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Models\UserOrganisasi;
use App\Models\PenanggungJawabOrganisasi;
use App\Models\UserPerusahaan;
use App\Models\PenanggungJawabPerusahaan;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends BaseController
{
    public function error_login(): JsonResponse
    {
        return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
    }
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register_organisasi(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'namaOrganisasi' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'kotaDomisiliOrganisasi' => 'required|in:Makassar,Jakarta,Surabaya',
                'nomorTeleponOrganisasi' => 'required|regex:/[0-9]/',
                'namaLengkapPenanggungJawab' => 'required',
                'tanggalLahirPenanggungJawab' => 'required|date|date_format:Y-m-d|before:today',
                'alamatLengkapPenanggungJawab' => 'required',
                'emailPenanggungJawab' => 'required|email',
                'ktpPenanggungJawab' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 400);
            }

            $input = $request->all();
            if (
                count(UserOrganisasi::where("namaorganisasi", $input['namaOrganisasi'])->get()) == 0
                && count(User::where("email", $input['email'])->get()) == 0
            ) {
                $input['password'] = Hash::make($input['password']);

                $user = new User;
                $user->email = $input['email'];
                $user->password = $input['password'];
                $user->level = "organisasi";
                $user->save();

                $organisasi = new UserOrganisasi;
                $organisasi->namaorganisasi = $input['namaOrganisasi'];
                $organisasi->kotadomisiliorganisasi = $input['kotaDomisiliOrganisasi'];
                $organisasi->nomorteleponorganisasi = $input['nomorTeleponOrganisasi'];
                $organisasi->id_user = $user->id;
                $organisasi->save();

                $penanggungJawab = new PenanggungJawabOrganisasi;
                $penanggungJawab->namalengkappjo = $input['namaLengkapPenanggungJawab'];
                $penanggungJawab->tanggallahirpjo = $input['tanggalLahirPenanggungJawab'];
                $penanggungJawab->alamatlengkappjo = $input['alamatLengkapPenanggungJawab'];
                $penanggungJawab->emailpjo = $input['emailPenanggungJawab'];
                $penanggungJawab->id_organisasi = $organisasi->id;
                $penanggungJawab->ktppjo = $input['ktpPenanggungJawab'];
                $penanggungJawab->save();

                $success['email'] =  $input['email'];
                $success['level'] =  $user->level;
                $success['dataOrganisasi'] = $organisasi;
                $success['dataPenanggungJawab']['namalengkappjo'] = $penanggungJawab->namalengkappjo;
                $success['dataPenanggungJawab']['tanggallahirpjo'] = $penanggungJawab->tanggallahirpjo;
                $success['dataPenanggungJawab']['alamatlengkappjo'] = $penanggungJawab->alamatlengkappjo;
                $success['dataPenanggungJawab']['emailpjo'] = $penanggungJawab->emailpjo;
                $success['dataPenanggungJawab']['id_organisasi'] = $penanggungJawab->id_organisasi;
                $success['dataPenanggungJawab']['id'] = $penanggungJawab->id;
                $success['dataPenanggungJawab']['updated_at'] = $penanggungJawab->updated_at;
                $success['dataPenanggungJawab']['created_at'] = $penanggungJawab->created_at;

                return $this->sendResponse($success, 'Organisation registered successfully.');
            } else {
                return $this->sendError('Name Taken.', ['error' => "Name or email has been taken"], 400);
            }
        } catch (\Exception $e) {
            if (isset($organisasi)) {
                PenanggungJawabOrganisasi::where('id_organisasi', $organisasi->id)->delete();
                UserOrganisasi::where('id_organisasi', $organisasi->id)->delete();
            }
            if (isset($user)) $user->delete();
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function register_perusahaan(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'namaPerusahaan' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'kotaDomisiliPerusahaan' => 'required|in:Makassar,Jakarta,Surabaya',
                'nomorTeleponPerusahaan' => 'required|regex:/[0-9]/',
                'namaLengkapPenanggungJawab' => 'required',
                'tanggalLahirPenanggungJawab' => 'required|date|date_format:Y-m-d|before:today',
                'alamatLengkapPenanggungJawab' => 'required',
                'emailPenanggungJawab' => 'required|email',
                'ktpPenanggungJawab' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 400);
            }

            $input = $request->all();
            if (
                count(UserPerusahaan::where("namaperusahaan", $input['namaPerusahaan'])->get()) == 0
                && count(User::where("email", $input['email'])->get()) == 0
            ) {
                $input['password'] = Hash::make($input['password']);

                $user = new User;
                $user->email = $input['email'];
                $user->password = $input['password'];
                $user->level = "perusahaan";
                $user->save();

                $perusahaan = new UserPerusahaan;
                $perusahaan->namaperusahaan = $input['namaPerusahaan'];
                $perusahaan->kotadomisiliperusahaan = $input['kotaDomisiliPerusahaan'];
                $perusahaan->nomorteleponperusahaan = $input['nomorTeleponPerusahaan'];
                $perusahaan->id_user = $user->id;
                $perusahaan->save();

                $penanggungJawab = new PenanggungJawabPerusahaan;
                $penanggungJawab->namalengkappjp = $input['namaLengkapPenanggungJawab'];
                $penanggungJawab->tanggallahirpjp = $input['tanggalLahirPenanggungJawab'];
                $penanggungJawab->alamatlengkappjp = $input['alamatLengkapPenanggungJawab'];
                $penanggungJawab->emailpjp = $input['emailPenanggungJawab'];
                $penanggungJawab->id_perusahaan = $perusahaan->id;
                $penanggungJawab->ktppjp = $input['ktpPenanggungJawab'];
                $penanggungJawab->save();

                $success['email'] =  $input['email'];
                $success['level'] =  $user->level;
                $success['dataPerusahaan'] = $perusahaan;
                $success['dataPenanggungJawab']['namalengkappjp'] = $penanggungJawab->namalengkappjp;
                $success['dataPenanggungJawab']['tanggallahirpjp'] = $penanggungJawab->tanggallahirpjp;
                $success['dataPenanggungJawab']['alamatlengkappjp'] = $penanggungJawab->alamatlengkappjp;
                $success['dataPenanggungJawab']['emailpjp'] = $penanggungJawab->emailpjp;
                $success['dataPenanggungJawab']['id_perusahaan'] = $penanggungJawab->id_perusahaan;
                $success['dataPenanggungJawab']['id'] = $penanggungJawab->id;
                $success['dataPenanggungJawab']['updated_at'] = $penanggungJawab->updated_at;
                $success['dataPenanggungJawab']['created_at'] = $penanggungJawab->created_at;

                return $this->sendResponse($success, 'User Perusahaan register successfully.');
            } else {
                return $this->sendError('Name Taken.', ['error' => "Name or email has been taken"], 400);
            }
        } catch (\Exception $e) {
            if (isset($perusahaan)) {
                DB::table('penanggung_jawab_perusahaan')->where('id_perusahaan', $perusahaan->id)->delete();
                DB::table('user_perusahaan')->where('id_perusahaan', $perusahaan->id)->delete();
            }
            if (isset($user)) $user->delete();
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                $success['token'] =  $user->createToken('auth-token')->plainTextToken;
                $success['level'] =  $user->level;
                $success['user'] = $user->level == "organisasi" ? $user->organisasi : $user->perusahaan;

                return $this->sendResponse($success, 'User login successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                $level = Auth::user()->level;
                $request->user()->tokens->each(function ($token) {
                    $token->delete();
                });

                return $this->sendResponse(['status' => 'User Logged Out', 'level' => $level], 'User Logout successfully.');
            } else {
                return $this->sendError('Bad Request.', ['error' => 'You are not logged in'], 400);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function getSession(Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                $level = Auth::user()->level;
                return $this->sendResponse(['level' => $level], 'User Session Retrieved successfully.');
            } else {
                return $this->sendError('Bad Request.', ['error' => 'You are not logged in'], 400);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function userData(): JsonResponse
    {
        try {
            if (Auth::id()) {
                $user = Auth::user();

                switch ($user->level) {
                    case "perusahaan":
                        $userData = UserPerusahaan::where('id_user', $user->id)
                            ->first();
                        $penanggungJawab = $userData->penanggungjawab;
                        $datas = [
                            "id_perusahaan" => $userData->id_perusahaan,
                            "namaPerusahaan" => $userData->namaperusahaan,
                            "email" => $user->email,
                            "kotaDomisiliPerusahaan" => $userData->kotadomisiliperusahaan,
                            "nomorTeleponPerusahaan" => $userData->nomorteleponperusahaan,
                            "penanggungJawab" => [
                                "namaLengkap" => $penanggungJawab->namalengkappjp,
                                "tanggalLahir" => $penanggungJawab->tanggallahirpjp,
                                "email" => $penanggungJawab->emailpjp,
                                "alamatLengkap" => $penanggungJawab->alamatlengkappjp
                            ]
                        ];
                        break;
                    case "organisasi":
                        $userData = UserOrganisasi::where('id_user', $user->id)
                            ->first();
                        $penanggungJawab = $userData->penanggungjawab;
                        $datas = [
                            "id_organisasi" => $userData->id_organisasi,
                            "namaOrganisasi" => $userData->namaorganisasi,
                            "email" => $user->email,
                            "kotaDomisiliOrganisasi" => $userData->kotadomisiliorganisasi,
                            "nomorTeleponOrganisasi" => $userData->nomorteleponorganisasi,
                            "penanggungJawab" => [
                                "namaLengkap" => $penanggungJawab->namalengkappjo,
                                "tanggalLahir" => $penanggungJawab->tanggallahirpjo,
                                "email" => $penanggungJawab->emailpjo,
                                "alamatLengkap" => $penanggungJawab->alamatlengkappjo
                            ]
                        ];
                        break;
                }
                $success['level'] = $user->level;
                $success['id_user'] = $user->id;
                $success['user'] = $datas;

                return $this->sendResponse($success, 'User data retrieved successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }
}
