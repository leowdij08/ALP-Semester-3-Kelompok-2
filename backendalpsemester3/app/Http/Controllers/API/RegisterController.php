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

            $dataOrganisasi = [
                "namaorganisasi" => $input['namaOrganisasi'],
                "kotadomisiliorganisasi" => $input['kotaDomisiliOrganisasi'],
                "nomorteleponorganisasi" => $input['nomorTeleponOrganisasi'],
            ];
            DB::table('user_organisasi')->insert([
                ...$dataOrganisasi,
                "id_user" => $user->id,
            ]);
            $idOrganisasi = DB::table('user_organisasi')->where('id_user', $user->id)->value('id_organisasi');

            $dataPenanggungJawab = [
                "namalengkappjo" => $input['namaLengkapPenanggungJawab'],
                "tanggallahirpjo" => $input['tanggalLahirPenanggungJawab'],
                "alamatlengkappjo" => $input['alamatLengkapPenanggungJawab'],
                "emailpjo" => $input['emailPenanggungJawab'],
            ];
            DB::table('penanggung_jawab_organisasi')->insert([
                ...$dataPenanggungJawab,
                "id_organisasi" => $idOrganisasi,
                "ktppjo" => "pathToFile",
            ]);

            $success['email'] =  $input['email'];
            $success['level'] =  $user->level;
            $success['dataOrganisasi'] = $dataOrganisasi;
            $success['dataPenanggungJawab'] = $dataPenanggungJawab;

            return $this->sendResponse($success, 'User Organisasi register successfully.');
        } catch (\Exception $e) {
            if (isset($idOrganisasi)) {
                DB::table('penanggung_jawab_organisasi')->where('id_organisasi', $idOrganisasi)->delete();
                DB::table('user_organisasi')->where('id_organisasi', $idOrganisasi)->delete();
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

            $dataPerusahaan = [
                "namaperusahaan" => $input['namaPerusahaan'],
                "kotadomisiliperusahaan" => $input['kotaDomisiliPerusahaan'],
                "nomorteleponperusahaan" => $input['nomorTeleponPerusahaan'],
            ];
            DB::table('user_perusahaan')->insert([
                ...$dataPerusahaan,
                "id_user" => $user->id,
            ]);
            $idPerusahaan = DB::table('user_perusahaan')->where('id_user', $user->id)->value('id_perusahaan');

            $dataPenanggungJawab = [
                "namalengkappjp" => $input['namaLengkapPenanggungJawab'],
                "tanggallahirpjp" => $input['tanggalLahirPenanggungJawab'],
                "alamatlengkappjp" => $input['alamatLengkapPenanggungJawab'],
                "emailpjp" => $input['emailPenanggungJawab'],
            ];
            DB::table('penanggung_jawab_perusahaan')->insert([
                ...$dataPenanggungJawab,
                "id_perusahaan" => $idPerusahaan,
                "ktppjp" => "pathToFile",
            ]);

            $success['email'] =  $input['email'];
            $success['level'] =  $user->level;
            $success['dataPerusahaan'] = $dataPerusahaan;
            $success['dataPenanggungJawab'] = $dataPenanggungJawab;

            return $this->sendResponse($success, 'User Perusahaan register successfully.');
        } catch (\Exception $e) {
            if (isset($idPerusahaan)) {
                DB::table('penanggung_jawab_perusahaan')->where('id_perusahaan', $idPerusahaan)->delete();
                DB::table('user_perusahaan')->where('id_perusahaan', $idPerusahaan)->delete();
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
    }
}
