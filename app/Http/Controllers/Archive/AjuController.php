<?php

namespace App\Http\Controllers\Archive;

use App\Http\Controllers\Controller;
use App\Models\TAju;
use App\Models\MDepartment;
use App\Models\TAjuDetail;
use App\Models\TArchive;
use App\Models\User;
use App\Models\MDocumentType;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AjuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);
        $sortField = $request->input('sort_field', 'created_at'); // default sorting
        $sortDirection = $request->input('sort_direction', 'desc'); // default descending

        // Validasi kolom yang bisa diurutkan
        $validSortFields = ['no_docs', 'created_at', 'department_id', 'created_by'];

        // Query awal
        $ajus = TAju::with([
            'department',
            'createdByUser',
            'details' => function ($query) {
                $query->with(['archive' => function ($q) {
                    $q->whereNotNull('pdfblob')->where('pdfblob', '!=', '');
                }]);
            }
        ])
            ->where('active_y_n', 'Y')
            ->whereHas('details.archive', function ($query) {
                $query->whereNotNull('pdfblob')->where('pdfblob', '!=', '');
            });

        // Pencarian
        if ($search) {
            $ajus->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('department', function ($query) use ($search) {
                        $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        // Sorting
        if (in_array($sortField, $validSortFields)) {
            if ($sortField === 'department_id') {
                $ajus->join('m_departments as dep', 't_aju.department_id', '=', 'dep.id')
                    ->orderBy('dep.name', $sortDirection);
            } elseif ($sortField === 'created_by') {
                $ajus->join('users', 't_aju.created_by', '=', 'users.id')
                    ->orderBy('users.name', $sortDirection);
            } else {
                $ajus->orderBy($sortField, $sortDirection);
            }
        }

        // Pagination
        $ajus = $ajus->select('t_aju.*')->paginate($perPage);

        // Department & SubDepartment
        $deps = MDepartment::getDepartments();
        $subDeps = MDepartment::getSubDepartments();

        return view('pages.archive.AJU.index_list', compact('ajus', 'deps', 'subDeps', 'perPage', 'sortField', 'sortDirection'));
    }



    // NEW
    public function indexNewAju(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);
        $sortField = $request->input('sort_field', 'created_at'); // default sorting
        $sortDirection = $request->input('sort_direction', 'desc'); // default descending

        // Validasi kolom yang bisa diurutkan
        $validSortFields = ['no_docs', 'created_at', 'department_id', 'created_by'];

        // Query awal
        $ajus = TAju::with([
            'department',
            'createdByUser',
            'details' => function ($query) {
                $query->with(['archive' => function ($q) {
                    $q->whereNotNull('pdfblob')->where('pdfblob', '!=', '');
                }]);
            }
        ])
            ->where('active_y_n', 'Y')
            ->whereHas('details.archive', function ($query) {
                $query->whereNotNull('pdfblob')->where('pdfblob', '!=', '');
            });

        // Pencarian
        if ($search) {
            $ajus->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('department', function ($query) use ($search) {
                        $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        // Sorting
        if (in_array($sortField, $validSortFields)) {
            if ($sortField === 'department_id') {
                $ajus->join('m_departments as dep', 't_aju.department_id', '=', 'dep.id')
                    ->orderBy('dep.name', $sortDirection);
            } elseif ($sortField === 'created_by') {
                $ajus->join('users', 't_aju.created_by', '=', 'users.id')
                    ->orderBy('users.name', $sortDirection);
            } else {
                $ajus->orderBy($sortField, $sortDirection);
            }
        }

        // Pagination
        $ajus = $ajus->select('t_aju.*')->paginate($perPage);

        // Department & SubDepartment
        $deps = MDepartment::getDepartments();
        $subDeps = MDepartment::getSubDepartments();


        return view('pages.archive.AJU.index_new', compact('ajus', 'deps', 'subDeps', 'perPage', 'sortField', 'sortDirection'));
    }
    public function formNew(Request $request)
    {
        $deps = MDepartment::getDepartments();
        $subDeps = MDepartment::getSubDepartments();
        $archives = TArchive::paginate(10);
        // Ambil data dari t_aju_detail beserta relasinya dengan filter berdasarkan id_aju
        $ajuDetails = TAjuDetail::with(['archive'])
            ->where('id_aju', $request->input('id_aju'))
            ->get();

        $aju = TAju::where('id_aju', $request->input('id_aju'))->first();

        $users = User::all();

        $documentTypes = MDocumentType::where('status', 'active')->get();

        return view('pages.archive.AJU.input.formNew', compact('deps', 'subDeps', 'ajuDetails', 'aju', 'archives', 'users', 'documentTypes'));
    }


    public function formNewGetData(Request $request)
    {

        $idAju = $request->input('id_aju');

        $deps = MDepartment::getDepartments();
        $subDeps = MDepartment::getSubDepartments();
        $archives = TArchive::paginate(10);

        $ajuDetails = TAjuDetail::with(['archive'])
            ->where('id_aju', $idAju)
            ->get();

        $aju = TAju::where('id_aju', $idAju)->first();

        $users = User::all();

        return view('pages.archive.AJU.input.formNew', compact('deps', 'subDeps', 'ajuDetails', 'aju', 'archives', 'users'));
    }


    public function getSubDepartments($departmentId)
    {
        $subDepartments = MDepartment::where('pid', $departmentId)->get();
        return response()->json($subDepartments);
    }

    public function checkAjuNumber(Request $request)
    {
        $ajuNumber = $request->query('id_aju');
        $exists = TAju::where('no_docs', $ajuNumber)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function suggestAjuNumber(Request $request)
    {

        $date = $request->input('date');
        // dd($date);
        if (!$date) {
            $date = now()->format('Y-m-d');
        }

        $year = date('Y', strtotime($date));
        $monthDay = date('md', strtotime($date));

        $lastAju = TAju::orderBy('id_aju', 'desc')->first();
        $lastNumber = $lastAju ? intval(substr($lastAju->no_docs, -3)) : 0;

        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $suggestedAjuNumber = 'AJU-' . $year . '-' . $monthDay . '-' . $newNumber;

        return response()->json(['suggested_id_aju' => $suggestedAjuNumber]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'id_aju' => 'required|string|max:255',
            'dep' => 'required|exists:m_department,id',
            'sub_dep' => 'required',
            'description' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Menyimpan data ke database
        try {
            DB::table('t_aju')->insert([
                'date' => $request->input('date'),
                'id_department' => $request->input('sub_dep'),
                'no_docs' => $request->input('id_aju'),
                'description' => $request->input('description'),
                'active_y_n' => 'Y',
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            session()->flash('old', $request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Archive added successfully!',
                'redirect_url' => route('index.formNew.GetData') // Kirim URL tujuan ke frontend
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upload document: ' . $e->getMessage()
            ], 500);
        }
    }


    public function storeModal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_modal' => 'required|date',
            'type_docs_modal' => 'required|string',
            'id_document' => 'required|string|max:255',
            'description_modal' => 'required|string',
            'files' => 'required|array',
            'files.*' => 'mimes:pdf,jpg,jpeg|max:25600', // 25MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $idAju = DB::table('t_aju')
            ->where('no_docs', $request->input('id_aju_modal'))
            ->value('id_aju');

        if (!$idAju) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID Aju tidak ditemukan!'
            ], 404);
        }

        $files = $request->file('files');

        try {
            foreach ($files as $file) {
                $fileName = $file->getClientOriginalName();
                $fileExtension = $file->getClientOriginalExtension();
                $fileData = file_get_contents($file->getRealPath());
                $base64EncodedData = base64_encode($fileData);

                $archive = TArchive::create([
                    'id_archieve' => $idAju,
                    'date' => $request->date_modal,
                    'doc_type' => $request->type_docs_modal,
                    'no_document' => $request->id_document,
                    'sub_dep' => $request->sub_dep_modal,
                    'description' => $request->description_modal,
                    'file_name' => $fileName,
                    'file_extension' => $fileExtension,
                    'pdfblob' => $base64EncodedData,
                    'active_y_n' => 'Y',
                    'created_by' => $request->user_email,
                    'created_at' => now(),
                ]);

                TAjuDetail::create([
                    'id_aju' => $idAju,
                    'id_archieve' => $archive->idrec,
                ]);
            }

            return redirect()->route('index.formNew.GetData', ['id_aju' => $idAju])
                ->with('success', 'Dokumen berhasil diunggah!');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengunggah dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeModalArchive(Request $request)
    {
        $request->validate([
            'id_aju_modal_archive' => 'required',
            'id_archieve' => 'required',
        ]);

        $idAju = DB::table('t_aju')
            ->where('no_docs', $request->input('id_aju_modal_archive'))
            ->value('id_aju');

        TAjuDetail::create([
            'id_aju' => $idAju,
            'id_archieve' => $request->input('id_archieve'),
        ]);

        return redirect()->route('index.formNew.GetData', ['id_aju' => $idAju])
            ->with('success', 'Archive added successfully!');
    }

    public function destroy($id)
    {
        try {
            $detail = TAjuDetail::findOrFail($id);
            $detail->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data detail AJU berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }


    // EDIT
    public function indexEditAju(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);
        $sortField = $request->input('sort_field', 'created_at'); // default sorting
        $sortDirection = $request->input('sort_direction', 'desc'); // default descending

        // Validasi kolom yang bisa diurutkan
        $validSortFields = ['no_docs', 'created_at', 'department_id', 'created_by'];

        // Query awal
        $ajus = TAju::with([
            'department',
            'createdByUser',
            'details' => function ($query) {
                $query->with(['archive' => function ($q) {
                    $q->whereNotNull('pdfblob')->where('pdfblob', '!=', '');
                }]);
            }
        ])
            ->where('active_y_n', 'Y')
            ->whereHas('details.archive', function ($query) {
                $query->whereNotNull('pdfblob')->where('pdfblob', '!=', '');
            });

        // Pencarian
        if ($search) {
            $ajus->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('department', function ($query) use ($search) {
                        $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        // Sorting
        if (in_array($sortField, $validSortFields)) {
            if ($sortField === 'department_id') {
                $ajus->join('m_departments as dep', 't_aju.department_id', '=', 'dep.id')
                    ->orderBy('dep.name', $sortDirection);
            } elseif ($sortField === 'created_by') {
                $ajus->join('users', 't_aju.created_by', '=', 'users.id')
                    ->orderBy('users.name', $sortDirection);
            } else {
                $ajus->orderBy($sortField, $sortDirection);
            }
        }

        // Pagination
        $ajus = $ajus->select('t_aju.*')->paginate($perPage);

        // Department & SubDepartment
        $deps = MDepartment::getDepartments();
        $subDeps = MDepartment::getSubDepartments();

        return view('pages.archive.AJU.index_edit', compact('ajus', 'deps', 'subDeps', 'perPage', 'sortField', 'sortDirection'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'dep' => 'required|exists:m_department,id',
            'sub_dep' => 'required',
            'description' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Update data di database
        try {
            $updated = DB::table('t_aju')
                ->where('no_docs', $id)
                ->update([
                    'date' => $request->input('date'),
                    'id_department' => $request->input('sub_dep'),
                    'no_docs' => $id,
                    'description' => $request->input('description'),
                    'updated_by' => Auth::id(),
                    'updated_at' => now(),
                ]);

            if ($updated) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Archive updated successfully!',
                    'redirect_url' => route('index.formNew.GetData')
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No changes made or invalid ID.'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeModalUpdate(Request $request)
    {
        // dd($request->all());
        // Validasi input
        $validator = Validator::make($request->all(), [
            'date_modal' => 'required|date',
            'type_docs_modal' => 'required|string',
            'id_document' => 'required|string|max:255',
            'description_modal' => 'required|string',
            'files' => 'required|array',
            'files.*' => 'mimes:pdf|max:25600', // 25MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Ambil ID dari TAju berdasarkan no_docs
        $idAju = DB::table('t_aju')
            ->where('no_docs', $request->input('id_aju_modal'))
            ->value('id_aju');

        if (!$idAju) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID Aju tidak ditemukan!'
            ], 404);
        }

        // Mengambil file dari request
        $files = $request->file('files');

        try {
            foreach ($files as $file) {
                // Mendapatkan nama file
                $fileName = $file->getClientOriginalName();

                // Membaca file sebagai string dan mengubahnya menjadi Base64
                $fileData = file_get_contents($file->getRealPath());
                $base64EncodedData = base64_encode($fileData);

                // Menyimpan data ke database menggunakan model TArchive
                $archive = TArchive::create([
                    'id_archieve' => $idAju,
                    'date' => $request->date_modal,
                    'doc_type' => $request->type_docs_modal,
                    'no_document' => $request->id_document,
                    'sub_dep' => $request->sub_dep_modal,
                    'description' => $request->description_modal,
                    'file_name' => $fileName,
                    'pdfblob' => $base64EncodedData,
                    'active_y_n' => 'Y',
                    'created_by' => $request->user_email,
                    'created_at' => now(),
                ]);

                // Menyimpan ke t_aju_detail menggunakan model TAjuDetail
                TAjuDetail::create([
                    'id_aju' => $idAju,
                    'id_archieve' => $archive->idrec,
                ]);
            }

            // Redirect ke route setelah berhasil
            return redirect()->route('index.formUpdate.GetData', ['id_aju' => $idAju])
                ->with('success', 'Archive added successfully!');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upload document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function formUpdateGetData(Request $request)
    {

        $idAju = $request->input('id_aju');

        $deps = MDepartment::getDepartments();
        $subDeps = MDepartment::getSubDepartments();
        $search = $request->input('search');
        $archives = TArchive::with('subDepartment.parent')
            ->when($search, function ($query, $search) {
                return $query->where('doc_type', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhere('no_document', 'like', "%$search%")
                    ->orWhereHas('subDepartment', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('subDepartment.parent', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            })
            ->get();

        $ajuDetails = TAjuDetail::with(['archive.subDepartment.parent', 'archive.createdByUser'])
            ->where('id_aju', $idAju)
            ->get();
        $users = User::all();

        $aju = TAju::where('id_aju', $idAju)->first();

        return view('pages.archive.AJU.input.formEdit', compact('deps', 'subDeps', 'ajuDetails', 'aju', 'archives', 'users'));
    }

    public function searchArchives(Request $request)
    {
        $search = $request->input('search');

        $archives = TArchive::with('subDepartment.parent')
            ->when($search, function ($query, $search) {
                return $query->where('doc_type', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhere('no_document', 'like', "%$search%")
                    ->orWhere('file_name', 'like', "%$search%")
                    ->orWhereHas('subDepartment', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('subDepartment.parent', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            })
            ->get()
            ->map(function ($archive) {
                return [
                    'idrec' => $archive->idrec,
                    'doc_type' => $archive->doc_type,
                    'date' => $archive->date,
                    'description' => $archive->description,
                    'no_document' => $archive->no_document,
                    'file_name' => $archive->file_name,
                    'pdfblob' => $archive->pdfblob,
                    'sub_department' => [
                        'name' => $archive->subDepartment->name ?? null,
                        'parent' => [
                            'name' => $archive->subDepartment->parent->name ?? null
                        ]
                    ]
                ];
            });

        return response()->json([
            'archives' => $archives
        ]);
    }

    public function storeModalArchiveUpdate(Request $request)
    {
        $request->validate([
            'id_aju_modal_archive' => 'required',
            'id_archieve' => 'required',
        ]);

        $idAju = DB::table('t_aju')
            ->where('no_docs', $request->input('id_aju_modal_archive'))
            ->value('id_aju');

        TAjuDetail::create([
            'id_aju' => $idAju,
            'id_archieve' => $request->input('id_archieve'),
        ]);

        return redirect()->route('index.formUpdate.GetData', ['id_aju' => $idAju])
            ->with('success', 'Archive added successfully!');
    }

    // DELETE
    public function indexDeleteAju(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);
        $sortField = $request->input('sort_field', 'created_at'); // default sorting
        $sortDirection = $request->input('sort_direction', 'desc'); // default descending

        // Validasi kolom yang bisa diurutkan
        $validSortFields = ['no_docs', 'created_at', 'department_id', 'created_by'];

        // Query awal
        $ajus = TAju::with([
            'department',
            'createdByUser',
            'details' => function ($query) {
                $query->with(['archive' => function ($q) {
                    $q->whereNotNull('pdfblob')->where('pdfblob', '!=', '');
                }]);
            }
        ])
            ->where('active_y_n', 'Y')
            ->whereHas('details.archive', function ($query) {
                $query->whereNotNull('pdfblob')->where('pdfblob', '!=', '');
            });

        // Pencarian
        if ($search) {
            $ajus->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('department', function ($query) use ($search) {
                        $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        // Sorting
        if (in_array($sortField, $validSortFields)) {
            if ($sortField === 'department_id') {
                $ajus->join('m_departments as dep', 't_aju.department_id', '=', 'dep.id')
                    ->orderBy('dep.name', $sortDirection);
            } elseif ($sortField === 'created_by') {
                $ajus->join('users', 't_aju.created_by', '=', 'users.id')
                    ->orderBy('users.name', $sortDirection);
            } else {
                $ajus->orderBy($sortField, $sortDirection);
            }
        }

        // Pagination
        $ajus = $ajus->select('t_aju.*')->paginate($perPage);

        // Department & SubDepartment
        $deps = MDepartment::getDepartments();
        $subDeps = MDepartment::getSubDepartments();


        return view('pages.archive.AJU.index_delete', compact('ajus', 'deps', 'subDeps', 'perPage', 'sortField', 'sortDirection'));
    }

    public function softDelete($id)
    {
        try {
            // Find the AJU record
            $aju = TAju::findOrFail($id);

            // Update all related archives to inactive
            TArchive::where('id_aju', $id)
                ->update(['active_y_n' => 'N']);

            return redirect()->route('index.newaju')
                ->with('success', 'AJU and related documents have been deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('index.newaju')
                ->with('error', 'Failed to delete AJU: ' . $e->getMessage());
        }
    }
}
