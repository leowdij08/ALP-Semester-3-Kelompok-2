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
        return $this->sendError('Bad Request.', "Please Login First");
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
                'kotaDomisiliOrganisasi' => 'in:Makassar,Jakarta,Surabaya',
                'nomorTeleponOrganisasi' => 'required|regex:/[0-9]/',
                'namaLengkapPenanggungJawab' => 'required',
                'tanggalLahirPenanggungJawab' => 'required|date|date_format:Y-m-d|before:today',
                'alamatLengkapPenanggungJawab' => 'required',
                'emailPenanggungJawab' => 'required|email',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $input = $request->all();
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

            $penanggungJawab = new PenanggungJawabOrganisasi;
            $penanggungJawab->namalengkappjo = $input['namaLengkapPenanggungJawab'];
            $penanggungJawab->tanggallahirpjo = $input['tanggalLahirPenanggungJawab'];
            $penanggungJawab->alamatlengkappjo = $input['alamatLengkapPenanggungJawab'];
            $penanggungJawab->emailpjo = $input['emailPenanggungJawab'];
            $penanggungJawab->id_organisasi = $organisasi->id;
            $penanggungJawab->ktppjo = "pathToFile";

            $success['email'] =  $input['email'];
            $success['level'] =  $user->level;
            $success['dataOrganisasi'] = $organisasi;
            $success['dataPenanggungJawab'] = $penanggungJawab;

            return $this->sendResponse($success, 'User Organisasi register successfully.');
        } catch (\Exception $e) {
            if (isset($organisasi)) {
                PenanggungJawabOrganisasi::where('id_organisasi', $organisasi->id)->delete();
                UserOrganisasi::where('id_organisasi', $organisasi->id)->delete();
            }
            $user->delete();
            return $this->sendError('Server Error.', $e->getMessage());
        }
    }

    public function register_perusahaan(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'namaPerusahaan' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'kotaDomisiliPerusahaan' => 'in:Makassar,Jakarta,Surabaya',
                'nomorTeleponPerusahaan' => 'required|regex:/[0-9]/',
                'namaLengkapPenanggungJawab' => 'required',
                'tanggalLahirPenanggungJawab' => 'required|date|date_format:Y-m-d|before:today',
                'alamatLengkapPenanggungJawab' => 'required',
                'emailPenanggungJawab' => 'required|email',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $input = $request->all();
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

            $penanggungJawab = new PenanggungJawabPerusahaan;
            $penanggungJawab->namalengkappjp = $input['namaLengkapPenanggungJawab'];
            $penanggungJawab->tanggallahirpjp = $input['tanggalLahirPenanggungJawab'];
            $penanggungJawab->alamatlengkappjp = $input['alamatLengkapPenanggungJawab'];
            $penanggungJawab->emailpjp = $input['emailPenanggungJawab'];
            $penanggungJawab->id_perusahaan = $perusahaan->id;
            $penanggungJawab->ktppjp = "pathToFile";

            $success['email'] =  $input['email'];
            $success['level'] =  $user->level;
            $success['dataPerusahaan'] = $perusahaan;
            $success['dataPenanggungJawab'] = $penanggungJawab;

            return $this->sendResponse($success, 'User Perusahaan register successfully.');
        } catch (\Exception $e) {
            if (isset($perusahaan)) {
                DB::table('penanggung_jawab_perusahaan')->where('id_perusahaan', $perusahaan->id)->delete();
                DB::table('user_perusahaan')->where('id_perusahaan', $perusahaan->id)->delete();
            }
            $user->delete();
            return $this->sendError('Server Error.', $e->getMessage());
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

                return $this->sendResponse($success, 'User login successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage());
        }
    }

    public function userData(Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                $user = Auth::user();
                $userData = User::where('level', $user->level)
                    ->where('id_user', $user->id)
                    ->first();

                switch ($user->level) {
                    case "perusahaan":
                        $datas = [
                            "namaPerusahaan" => $userData->namaperusahaan,
                            "kotaDomisiliPerusahaan" => $userData->kotadomisiliperusahaan,
                            "nomorTeleponPerusahaan" => $userData->nomorteleponperusahaan,
                        ];
                        break;
                    case "organisasi":
                        $datas = [
                            "namaOrganisasi" => $userData->namaorganisasi,
                            "kotaDomisiliOrganisasi" => $userData->kotadomisiliorganisasi,
                            "nomorTeleponOrganisasi" => $userData->nomorteleponorganisasi,
                        ];
                        break;
                }
                $success['level'] = $user->level;
                $success['user'] = $datas;

                return $this->sendResponse($success, 'User data retrieved successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage());
        }
    }
}
