<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Models\UserOrganisasi;
use App\Models\PenanggungJawabOrganisasi;
use App\Models\UserPerusahaan;
use App\Models\PenanggungJawabPerusahaan;
use App\Models\Chat;
use App\Models\PesanChat;
use App\Models\LampiranPesan;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

class ChatController extends BaseController
{
    public function getAll(): JsonResponse
    {
        try {
            if (Auth::id()) {
                if (Auth::user()->level == "perusahaan") {
                    $perusahaan = UserPerusahaan::where("id_user", Auth::user()->id)->first();
                    $chat = Chat::where("id_perusahaan", $perusahaan->id_perusahaan)->get()->map(function ($detailChat) {
                        $unreadCount = PesanChat::where("id_chat", $detailChat->id_chat)->where("pengirimisperusahaan", false)->where("dibaca", false)->count();
                        $lastPesanChat = PesanChat::where("id_chat", $detailChat->id_chat)->orderBy("waktukirim", "desc")->limit(1)->get()->map(function ($pesanChat) {
                            return [
                                "pengirimIsSelf" => !!$pesanChat->pengirimisperusahaan,
                                "waktu_kirim" => $pesanChat->waktukirim,
                                "dibaca" => $pesanChat->dibaca,
                                "waktu_baca" => $pesanChat->dibaca ? $pesanChat->waktubaca : null,
                                "isi_pesan" => $pesanChat->isipesan
                            ];
                        });
                        $temanBicara = $detailChat->organisasi;
                        return [
                            "unreadCount" => $unreadCount,
                            "lastChat" => $lastPesanChat,
                            "dataTemanBicara" => ["nama" => $temanBicara->namaorganisasi, "id" => $temanBicara->id_organisasi],
                            "idChat" => $detailChat->id_chat
                        ];
                    });
                } else {
                    $organisasi = Auth::user()->organisasi;
                    $chat = Chat::where("id_organisasi", $organisasi->id_organisasi)->get()->map(function ($detailChat) {
                        $unreadCount = PesanChat::where("id_chat", $detailChat->id_chat)->where("pengirimisperusahaan", true)->where("dibaca", false)->count();
                        $lastPesanChat = PesanChat::where("id_chat", $detailChat->id_chat)->orderBy("waktukirim", "desc")->limit(1)->get()->map(function ($pesanChat) {
                            return [
                                "pengirimIsSelf" => !$pesanChat->pengirimisperusahaan,
                                "waktu_kirim" => $pesanChat->waktukirim,
                                "dibaca" => $pesanChat->dibaca,
                                "waktu_baca" => $pesanChat->dibaca ? $pesanChat->waktubaca : null,
                                "isi_pesan" => $pesanChat->isipesan
                            ];
                        });
                        $temanBicara = $detailChat->perusahaan;
                        return [
                            "unreadCount" => $unreadCount,
                            "lastChat" => $lastPesanChat,
                            "dataTemanBicara" => ["nama" => $temanBicara->namaperusahaan, "id" => $temanBicara->id_perusahaan],
                            "idChat" => $detailChat->id_chat
                        ];
                    });
                }

                return $this->sendResponse($chat, 'Chats retrieved successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function getById($idChat): JsonResponse
    {
        try {
            if (Auth::id()) {
                $chat = Chat::where("id_chat", $idChat)->first();
                if ($chat->count() > 0) {
                    if (Auth::user()->level == "perusahaan") {
                        if ($chat->perusahaan->id_user == Auth::user()->id) {
                            PesanChat::where("id_chat", $idChat)->where("pengirimisperusahaan", false)->update(['dibaca' => true, 'waktubaca' => now()]);
                            $dataChat = PesanChat::where("id_chat", $idChat)->get()->map(function ($chat) {
                                $lampiran = $chat->lampirans;
                                return [
                                    "pengirimIsSelf" => !!$chat->pengirimisperusahaan,
                                    "waktu_kirim" => $chat->waktukirim,
                                    "dibaca" => $chat->dibaca,
                                    "waktu_baca" => $chat->dibaca ? $chat->waktubaca : null,
                                    "isi_pesan" => $chat->isipesan,
                                    "lampiran" => $lampiran == null ? null : ["tipeLampiran" => $lampiran->tipelampiran, "namaFile" => $lampiran->namafle, "urlfile" => $lampiran->urlfile]
                                ];
                            });
                        } else {
                            return $this->sendError('Forbidden.', ['error' => 'Not Your Chat'], 403);
                        }
                    } else {
                        if ($chat->organisasi->id_user == Auth::user()->id) {
                            PesanChat::where("id_chat", $idChat)->where("pengirimisperusahaan", true)->update(['dibaca' => true, 'waktubaca' => now()]);
                            $dataChat = PesanChat::where("id_chat", $idChat)->get()->map(function ($chat) {
                                $lampiran = $chat->lampirans;
                                return [
                                    "pengirimIsSelf" => !$chat->pengirimisperusahaan,
                                    "waktu_kirim" => $chat->waktukirim,
                                    "dibaca" => $chat->dibaca,
                                    "waktu_baca" => $chat->dibaca ? $chat->waktubaca : null,
                                    "isi_pesan" => $chat->isipesan,
                                    "lampiran" => $lampiran == null ? null : ["tipeLampiran" => $lampiran->tipelampiran, "namaFile" => $lampiran->namafle, "urlfile" => $lampiran->urlfile]
                                ];
                            });
                        } else {
                            return $this->sendError('Forbidden.', ['error' => 'Not Your Chat'], 403);
                        }
                    }

                    return $this->sendResponse($dataChat, 'Chat history retrieved successfully.');
                } else {
                    return $this->sendError('Not Found.', ['error' => 'Chat Not Found'], 404);
                }
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function sendChat(Request $request, $idPenerima): JsonResponse
    {
        try {
            if (Auth::id()) {
                $validator = Validator::make($request->all(), [
                    'isiPesan' => 'required'
                ]);

                if ($validator->fails()) {
                    return $this->sendError('Validation Error.', $validator->errors(), 400);
                }

                $input = $request->all();
                $user = Auth::user();
                if ($user->level == "perusahaan") {
                    if (UserOrganisasi::where('id_organisasi', $idPenerima)->count() > 0) {
                        $target = UserOrganisasi::where('id_organisasi', $idPenerima)->first();
                        $chat = Chat::where("id_perusahaan", UserPerusahaan::where("id_user", $user->id)->first()->id_perusahaan)->where("id_organisasi", $idPenerima);
                        if ($chat->count() == 0) {
                            $dataChat = Chat::create([
                                "id_perusahaan" => UserPerusahaan::where("id_user", $user->id)->first()->id_perusahaan,
                                "id_organisasi" => $idPenerima
                            ]);
                        } else {
                            $dataChat = $chat->first();
                        }
                        $pesan = PesanChat::create([
                            "id_chat" => $dataChat->id_chat,
                            "pengirimisperusahaan" => true,
                            "waktukirim" => now(),
                            "dibaca" => false,
                            "waktubaca" => null,
                            "isipesan" => $input['isiPesan']
                        ]);
                        if (isset($input['lampiranFile'])) {
                            $validator = Validator::make($request->all(), [
                                'lampiranFile' => 'required',
                                'tipeLampiran' => 'required',
                                'namaFile' => 'required',
                            ]);

                            if ($validator->fails()) {
                                return $this->sendError('Validation Error.', $validator->errors(), 400);
                            }

                            $lampiran = LampiranPesan::create([
                                "id_pesan" => $pesan->id_pesan,
                                "tipelampiran" => $input['tipeLampiran'],
                                "namafile" => $input['namaFile'],
                                "urlfile" => $input['lampiranFile']
                            ]);
                        }
                    } else {
                        return $this->sendError('Not Found.', ['error' => 'Chat Target Not Found'], 404);
                    }
                } else {
                    if (UserPerusahaan::where('id_perusahaan', $idPenerima)->count() > 0) {
                        $target = UserPerusahaan::where('id_perusahaan', $idPenerima)->first();
                        $chat = Chat::where("id_organisasi", $user->organisasi->id_organisasi)->where("id_perusahaan", $idPenerima);
                        if ($chat->count() == 0) {
                            $dataChat = Chat::create([
                                "id_organisasi" => $user->organisasi->id_organisasi,
                                "id_perusahaan" => $idPenerima
                            ]);
                        } else {
                            $dataChat = $chat->first();
                        }
                        $pesan = PesanChat::create([
                            "id_chat" => $dataChat->id_chat,
                            "pengirimisperusahaan" => false,
                            "waktukirim" => now(),
                            "dibaca" => false,
                            "waktubaca" => null,
                            "isipesan" => $input['isiPesan']
                        ]);
                        if (isset($input['lampiranFile'])) {
                            $validator = Validator::make($request->all(), [
                                'lampiranFile' => 'required',
                                'tipeLampiran' => 'required',
                                'namaFile' => 'required',
                            ]);

                            if ($validator->fails()) {
                                return $this->sendError('Validation Error.', $validator->errors(), 400);
                            }

                            $lampiran = LampiranPesan::create([
                                "id_pesan" => $pesan->id_pesan,
                                "tipelampiran" => $input['tipeLampiran'],
                                "namafile" => $input['namaFile'],
                                "urlfile" => $input['lampiranFile']
                            ]);
                        }
                    } else {
                        return $this->sendError('Not Found.', ['error' => 'Chat Target Not Found'], 404);
                    }
                }

                return $this->sendResponse(['isi_pesan' => $input['isiPesan'], 'lampiranFile' => isset($lampiran) ? $lampiran->urlfile : null, 'target' => $target->namaorganisasi ?? $target->namaperusahaan], 'Chat sent successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

}
