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
                    $perusahaan = Auth::user()->perusahaan;
                    $chat = Chat::where("id_perusahaan", $perusahaan->id_perusahaan)->all()->map(function ($detailChat) {
                        $unreadCount = PesanChat::where("id_chat", $detailChat->id_chat)->where("pengirimisperusahaan", false)->where("dibaca", false)->count();
                        $lastPesanChat = PesanChat::where("id_chat", $detailChat->id_chat)->orderBy("waktukirim", "desc")->first()->map(function ($pesanChat) {
                            return [
                                "pengirimIsSelf" => $pesanChat->pengirimisperusahaan,
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
                    $chat = Chat::where("id_organisasi", $organisasi->id_organisasi)->all()->map(function ($detailChat) {
                        $unreadCount = PesanChat::where("id_chat", $detailChat->id_chat)->where("pengirimisperusahaan", false)->where("dibaca", false)->count();
                        $lastPesanChat = PesanChat::where("id_chat", $detailChat->id_chat)->orderBy("waktukirim", "desc")->first()->map(function ($pesanChat) {
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
                $chat = Chat::where("id_chat", $idChat);
                if ($chat->count() > 0) {
                    if (Auth::user()->level == "perusahaan") {
                        if ($chat->perusahaans->id_user == Auth::user()->id) {
                            $dataChat = PesanChat::where("id_chat", $idChat)->get()->map(function ($chat) {
                                $lampiran = $chat->lampirans;
                                return [
                                    "pengirimIsSelf" => $chat->pengirimisperusahaan,
                                    "waktu_kirim" => $chat->waktukirim,
                                    "dibaca" => $chat->dibaca,
                                    "waktu_baca" => $chat->dibaca ? $chat->waktubaca : null,
                                    "isi_pesan" => $chat->isipesan,
                                    "lampiran" => ["tipeLampiran" => $lampiran->tipelampiran, "namaFile" => $lampiran->namafle, "urlfile" => $lampiran->urlfile]
                                ];
                            });
                        } else {
                            return $this->sendError('Forbidden.', ['error' => 'Not Your Chat'], 403);
                        }
                    } else {
                        if ($chat->organisasis->id_user == Auth::user()->id) {
                            $dataChat = PesanChat::where("id_chat", $idChat)->get()->map(function ($chat) {
                                $lampiran = $chat->lampirans;
                                return [
                                    "pengirimIsSelf" => !$chat->pengirimisperusahaan,
                                    "waktu_kirim" => $chat->waktukirim,
                                    "dibaca" => $chat->dibaca,
                                    "waktu_baca" => $chat->dibaca ? $chat->waktubaca : null,
                                    "isi_pesan" => $chat->isipesan,
                                    "lampiran" => ["tipeLampiran" => $lampiran->tipelampiran, "namaFile" => $lampiran->namafle, "urlfile" => $lampiran->urlfile]
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
                        $chat = Chat::where("id_perusahaan", $user->perusahaan->id_perusahaan)->where("id_organisasi", $idPenerima);
                        if ($chat->count() == 0) {
                            $dataChat = Chat::create([
                                "id_perusahaan" => $user->perusahaan->id_perusahaan,
                                "id_organisasi" => $idPenerima
                            ]);
                        }
                        $pesan = PesanChat::create([
                            "id_chat" => $dataChat->id_chat,
                            "pengirimisperusahaan" => true,
                            "waktu_kirim" => now(),
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
                        $chat = Chat::where("id_organisasi", $user->perusahaan->id_perusahaan)->where("id_perusahaan", $idPenerima);
                        if ($chat->count() == 0) {
                            $dataChat = Chat::create([
                                "id_organisasi" => $user->perusahaan->id_perusahaan,
                                "id_perusahaan" => $idPenerima
                            ]);
                        }
                        $pesan = PesanChat::create([
                            "id_chat" => $dataChat->id_chat,
                            "pengirimisperusahaan" => false,
                            "waktu_kirim" => now(),
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

                return $this->sendResponse(['isi_pesan' => $input['pesan'], 'lampiranFile' => $lampiran->urlfile, 'target' => $target->namaorganisasi ?? $target->namaperusahaan], 'Chat sent successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

}
