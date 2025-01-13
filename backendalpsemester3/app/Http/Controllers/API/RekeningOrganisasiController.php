Memory Full



ChatGPT

Share
You said:
<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\RekeningOrganisasi;
use App\Models\Acara;
use App\Models\LaporanPertanggungjawaban;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RekeningOrganisasiController extends BaseController
{
    public function getbyID($id): JsonResponse
    {
        try {
            if (Auth::id()) {
                $userData = RekeningOrganisasi::where('id_rekeningorganisasi', $id)
                    ->get()->map(
                        function ($item) {
                            return [
                                'id_organisasi' => $item->id_organisasi,
                                'nomorrekeningorganisasi' => $item->nomorrekeningorganisasi,
                                'namabankorganisasi' => $item->namabankorganisasi,
                                'pemilikrekeningorganisasi' => $item->pemilikrekeningorganisasi,
                            ];
                        }
                    );


                return $this->sendResponse($userData, 'RekeningOrganisasi retrieved successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid RekeningOrganisasi'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function search($keyword): JsonResponse
    {
        try {
            if (Auth::id()) {
                $dataUser = RekeningOrganisasi::whereRaw("concat(nomorrekeningorgansisasi) like ?", ["%$keyword%"])
                    ->get()->map(function ($item) {
                        return [
                            'id_organisasi' => $item->id_organisasi,
                            'nomorrekeningorganisasi' => $item->nomorrekeningorganisasi,
                            'namabankorganisasi' => $item->namabankorganisasi,
                            'pemilikrekeningorganisasi' => $item->pemilikrekeningorganisasi,
                        ];
                    });

                return $this->sendResponse($dataUser, 'RekeningOrganisasi searched successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid RekeningOrganisasi'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function update($idRekeningOrganisasi, Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                if (RekeningOrganisasi::where("id_rekeningorganisasi", $idRekeningOrganisasi)->count() > 0) {
                    $RekeningOrganisasi = RekeningOrganisasi::where("id_rekeningorganisasi", $idRekeningOrganisasi)->first();
                    if ($RekeningOrganisasi->id_organisasi == Auth::user()->id) {
                        $validator = Validator::make($request->all(), [
                            'nomorrekeningorganisasi' => 'required',
                            'namabankorganisasi' => 'required|in:BCA,BCA DIGITAL,Mandiri,BNI,DBS',
                            'pemilikrekeningorganisasi' => 'required',
                        ]);

                        if ($validator->fails()) {
                            return $this->sendError('Validation Error.', $validator->errors(), 400);
                        }

                        $input = $request->all();
                        $RekeningOrganisasi = RekeningOrganisasi::where('id_rekeningorganisasi', $idRekeningOrganisasi);
                        $data = [
                            "nomorrekeningorganisasi" => $input['nomorrekeningorganisasi'],
                            "namabankorganisasi" => $input['namabankorganisasi'],
                            "pemilikrekeningorganisasi" => $input['pemilikrekeningorganisasi'],
                        ];
                        $RekeningOrganisasi->update($data);
                        $dataRekeningOrganisasi = $RekeningOrganisasi->get()->map(function ($item) {
                            return [
                                'id_organisasi' => $item->id_organisasi,
                                'nomorrekeningorganisasi' => $item->nomorrekeningorganisasi,
                                'namabankorganisasi' => $item->namabankorganisasi,
                                'pemilikrekeningorganisasi' => $item->pemilikrekeningorganisasi,
                            ];
                        });

                        return $this->sendResponse($dataRekeningOrganisasi, 'RekeningOrganisasi updated successfully.');
                    } else {
                        return $this->sendError('Forbidden.', ['error' => 'Not Your RekeningOrganisasi'], 403);
                    }
                } else {
                    return $this->sendError('Event Not Found.', ['error' => 'No RekeningOrganisasi Was Found'], 404);
                }
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid RekeningOrganisasi'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function create($idRekeningOrganisasi, Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                if (RekeningOrganisasi::where("id_rekeningorganisasi", $idRekeningOrganisasi)->count() > 0) {
                    $RekeningOrganisasi = RekeningOrganisasi::where("id_rekeningorganisasi", $idRekeningOrganisasi)->first();
                    if ($RekeningOrganisasi->id_organisasi == Auth::user()->id) {
                        $validator = Validator::make($request->all(), [
                            'nomorrekeningorganisasi' => 'required',
                            'namabankorganisasi' => 'required|in:BCA,BCA DIGITAL,Mandiri,BNI,DBS',
                            'pemilikrekeningorganisasi' => 'required',
                        ]);

                        if ($validator->fails()) {
                            return $this->sendError('Validation Error.', $validator->errors(), 400);
                        }

                        $input = $request->all();
                        $data = [
                            "nomorrekeningorganisasi" => $input['nomorrekeningorganisasi'],
                            "namabankorganisasi" => $input['namabankorganisasi'],
                            "pemilikrekeningorganisasi" => $input['pemilikrekeningorganisasi'],
                        ];
                        $RekeningOrganisasi = LaporanPertanggungjawaban::create($data);
                        $dataRekeningOrganisasi = $RekeningOrganisasi->get()->map(function ($item) {
                            return [
                                'id_organisasi' => $item->id_organisasi,
                                'nomorrekeningorganisasi' => $item->nomorrekeningorganisasi,
                                'namabankorganisasi' => $item->namabankorganisasi,
                                'pemilikrekeningorganisasi' => $item->pemilikrekeningorganisasi,
                            ];
                        });

                        return $this->sendResponse($dataRekeningOrganisasi, 'RekeningOrganisasi updated successfully.');
                    } else {
                        return $this->sendError('Forbidden.', ['error' => 'Not Your RekeningOrganisasi'], 403);
                    }
                } else {
                    return $this->sendError('RekeningOrganisasi Not Found.', ['error' => 'No RekeningOrganisasi Was Found'], 404);
                }
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid RekeningOrganisasi'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }

    public function delete($idRekeningOrganisasi, Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                if (RekeningOrganisasi::where("id_rekeningoganisasi", $idRekeningOrganisasi)->count() > 0) {
                    if (RekeningOrganisasi::where("id_rekeningorganisasi", $idRekeningOrganisasi)->first()->id_organisasi == Auth::user()->id) {
                        return $this->sendResponse(['isDeleted' => RekeningOrganisasi::where('id_organisasi', $idRekeningOrganisasi)->delete() ? true : false], 'Report deleted successfully.');
                    } else {
                        return $this->sendError('Forbidden.', ['error' => 'Not Your Event'], 403);
                    }
                } else {
                    return $this->sendError('Event Not Found.', ['error' => 'No Event With That ID Was Found'], 404);
                }
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid Login'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 500);
        }
    }
}  Tolong buatkan pesan errornya jadi lebih baik dan nyambung dengan bahasa inggrisnya, itu syaa masih buat aneh
ChatGPT said:
ChatGPT
Berikut adalah versi yang diperbaiki dari pesan error agar lebih sesuai dengan konteks dan lebih natural dalam bahasa Inggris:

Rekening Organisasi Controller

Answer in chat instead
File telah berhasil dibuat. Jika ada bagian yang ingin disesuaikan atau perbaikan lain yang diperlukan, silakan beri tahu saya!











ChatGPT can make mistakes. Check important info.
?
ChatGPT says: File telah berhasil dibuat. Jika ada bagian yang ingin disesuaikan atau perbaikan lain yang diperlukan, silakan beri tahu saya!

Rekening Organisasi Controller



69
70
71
72
73
74
75
76
77
78
79
80
81
82
83
84
85
86
87
88
89
90
91
92
93
94
95
96
97
98
99
100
101
102
103
104
105
106
107
108
109
110
111
112
113
114
115
116
117
118
119
120
121
122
123
124
125
126
127
128
129
130
131
132
133
134
135
136
137
138
139
140
141
142
143
144
145
146
147
148
149
150
151
152
                    if ($RekeningOrganisasi->id_organisasi == Auth::user()->id) {
                        $validator = Validator::make($request->all(), [
                            'nomorrekeningorganisasi' => 'required',
                            'namabankorganisasi' => 'required|in:BCA,BCA DIGITAL,Mandiri,BNI,DBS',
                            'pemilikrekeningorganisasi' => 'required',
                        ]);

                        if ($validator->fails()) {
                            return $this->sendError('Validation Error.', $validator->errors(), 400);
                        }

                        $input = $request->all();
                        $data = [
                            "nomorrekeningorganisasi" => $input['nomorrekeningorganisasi'],
                            "namabankorganisasi" => $input['namabankorganisasi'],
                            "pemilikrekeningorganisasi" => $input['pemilikrekeningorganisasi'],
                        ];

                        $RekeningOrganisasi->update($data);

                        return $this->sendResponse($RekeningOrganisasi, 'RekeningOrganisasi updated successfully.');
                    } else {
                        return $this->sendError('Forbidden Access.', ['error' => 'You do not have permission to update this RekeningOrganisasi.'], 403);
                    }
                } else {
                    return $this->sendError('Not Found.', ['error' => 'RekeningOrganisasi with the given ID was not found.'], 404);
                }
            } else {
                return $this->sendError('Unauthorized Access.', ['error' => 'You are not authorized to update this RekeningOrganisasi.'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', ['error' => $e->getMessage()], 500);
        }
    }

    public function create(Request $request): JsonResponse
    {
        try {
            if (Auth::id()) {
                $validator = Validator::make($request->all(), [
                    'nomorrekeningorganisasi' => 'required',
                    'namabankorganisasi' => 'required|in:BCA,BCA DIGITAL,Mandiri,BNI,DBS',
                    'pemilikrekeningorganisasi' => 'required',
                ]);

                if ($validator->fails()) {
                    return $this->sendError('Validation Error.', $validator->errors(), 400);
                }

                $input = $request->all();
                $RekeningOrganisasi = RekeningOrganisasi::create($input);

                return $this->sendResponse($RekeningOrganisasi, 'RekeningOrganisasi created successfully.');
            } else {
                return $this->sendError('Unauthorized Access.', ['error' => 'You are not authorized to create this RekeningOrganisasi.'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', ['error' => $e->getMessage()], 500);
        }
    }

    public function delete($idRekeningOrganisasi): JsonResponse
    {
        try {
            if (Auth::id()) {
                if (RekeningOrganisasi::where("id_rekeningorganisasi", $idRekeningOrganisasi)->exists()) {
                    $RekeningOrganisasi = RekeningOrganisasi::where("id_rekeningorganisasi", $idRekeningOrganisasi)->first();
                    if ($RekeningOrganisasi->id_organisasi == Auth::user()->id) {
                        $RekeningOrganisasi->delete();
                        return $this->sendResponse(['isDeleted' => true], 'RekeningOrganisasi deleted successfully.');
                    } else {
                        return $this->sendError('Forbidden Access.', ['error' => 'You do not have permission to delete this RekeningOrganisasi.'], 403);
                    }
                } else {
                    return $this->sendError('Not Found.', ['error' => 'RekeningOrganisasi with the given ID was not found.'], 404);
                }
            } else {
                return $this->sendError('Unauthorized Access.', ['error' => 'You are not authorized to delete this RekeningOrganisasi.'], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', ['error' => $e->getMessage()], 500);
        }
    }
}
