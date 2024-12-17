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

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register_organisasi(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'namaOrganisasi' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'kotaDomisiliOrganisasi' => 'in:Makassar,Jakarta,Surabaya',
            'nomorTeleponOrganisasi' => 'required|regex:/[0-9]/',
            'namaLengkapPenanggungJawab' => 'required',
            'tanggalLahirPenanggungJawab' => 'required|date|date_format:Y-m-d|before:today',
            'alamatLengkapPenanggungJawab' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create([$input['email'], $input['password'], "organisasi"]);
        $organisasi = UserOrganisasi::create([
            $input['namaOrganisasi'],
            $input['kotaDomisiliOrganisasi'],
            $input['nomorTeleponOrganisasi'],
            $user->id,
        ]);
        PenanggungJawabOrganisasi::create([
            $organisasi->id,
            $input['namaLengkapPenanggungJawab'],
            $input['tanggalLahirPenanggungJawab'],
            $input['alamatLengkapPenanggungJawab'],
        ]);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['level'] =  $user->level;

        return $this->sendResponse($success, 'User register successfully.');
    }

    public function register_perusahaan(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'namaPerusahaan' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'kotaDomisiliPerusahaan' => 'in:Makassar,Jakarta,Surabaya',
            'nomorTeleponPerusahaan' => 'required|regex:/[0-9]/',
            'namaLengkapPenanggungJawab' => 'required',
            'tanggalLahirPenanggungJawab' => 'required|date|date_format:Y-m-d|before:today',
            'alamatLengkapPenanggungJawab' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create([$input['email'], $input['password'], "perusahaan"]);
        $perusahaan = UserPerusahaan::create([
            $input['namaPerusahaan'],
            $input['kotaDomisiliPerusahaan'],
            $input['nomorTeleponPerusahaan'],
            $user->id,
        ]);
        PenanggungJawabPerusahaan::create([
            $perusahaan->id,
            $input['namaLengkapPenanggungJawab'],
            $input['tanggalLahirPenanggungJawab'],
            $input['alamatLengkapPenanggungJawab'],
        ]);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['level'] =  $user->level;

        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['level'] =  $user->level;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    public function userData(Request $request): JsonResponse
    {
        if (Auth::id()) {
            $user = Auth::user();
            $userData = DB::select('select * from user_' . $user->level . ' where id_user = "' . $user->id . '" limit 1')[0];
            switch($user->level){
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
    }
}
