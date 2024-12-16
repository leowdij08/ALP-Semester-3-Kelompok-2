<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
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
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;

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
