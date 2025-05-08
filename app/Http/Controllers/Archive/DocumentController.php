<?php

namespace App\Http\Controllers\Archive;

use App\Http\Controllers\Controller;
use App\Models\TAju;
use App\Models\TArchive;
use App\Models\MDepartment;
use App\Models\MDocumentType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);
        $sortField = $request->input('sort_field', 'date'); // Default sort by date
        $sortDirection = $request->input('sort_direction', 'desc'); // Default descending

        $archives = TArchive::with(['ajuDetails', 'aju', 'subDepartment.parent', 'createdByUser'])
            ->where('active_y_n', 'Y');

        // Search functionality
        if ($search) {
            $archives->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(doc_type) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(file_name) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('aju', function ($query) use ($search) {
                        $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        // Sorting functionality
        $validSortFields = [ 'date', 'doc_type', 'description', 'no_document', 'sub_department_id', 'department_id', 'file_name', 'created_by' ];

        if (in_array($sortField, $validSortFields)) {
            // Handle special cases for relationships
            if ($sortField === 'sub_department_id') {
                $archives->join('m_departments as sub_dep', 't_archives.sub_department_id', '=', 'sub_dep.id')
                    ->orderBy('sub_dep.name', $sortDirection);
            } elseif ($sortField === 'department_id') {
                $archives->join('m_departments as sub_dep', 't_archives.sub_department_id', '=', 'sub_dep.id')
                    ->join('m_departments as dep', 'sub_dep.parent_id', '=', 'dep.id')
                    ->orderBy('dep.name', $sortDirection);
            } elseif ($sortField === 'created_by') {
                $archives->join('users', 't_archives.created_by', '=', 'users.id')
                    ->orderBy('users.name', $sortDirection);
            } else {
                $archives->orderBy($sortField, $sortDirection);
            }
        }

        $archives = $archives->paginate($perPage);

        $deps = MDepartment::getDepartments();
        $subDeps = MDepartment::getSubDepartments();

        return view('pages.archive.document.index_list', compact('archives', 'deps', 'subDeps', 'perPage', 'sortField', 'sortDirection'));
    }
    // NEW
    public function indexNewDocument(Request $request)
    {

        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        // Query untuk TArchive dengan relasi ajuDetails dan aju
        $archives = TArchive::with(['ajuDetails', 'aju', 'subDepartment.parent'])
            ->where('active_y_n', 'Y');

        // Jika ada pencarian
        if ($search) {
            $archives->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(doc_type) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(file_name) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('aju', function ($query) use ($search) {
                        $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        $archives = $archives->paginate($perPage);

        $deps = MDepartment::getDepartments();
        $subDeps = MDepartment::getSubDepartments();

        $users = User::all();

        $documentTypes = MDocumentType::where('status', 'Active')->orderBy('name')->get();

        return view('pages.archive.document.index_new', compact('archives', 'deps', 'subDeps', 'perPage', 'users', 'documentTypes'));
    }

    public function checkDocumentNumber(Request $request)
    {
        $documentNumber = $request->query('id_document');
        $docType = $request->query('doc_type');

        $exists = TArchive::where('no_document', $documentNumber)
            ->where('doc_type', $docType)
            ->where('active_y_n', 'Y')
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function suggestDocumentNumber(Request $request)
    {
        $date = $request->input('date');
        $docType = $request->input('doc_type'); // Get document type from request

        if (!$date) {
            $date = now()->format('Y-m-d');
        }

        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $day = date('d', strtotime($date));

        // Get the document type code (default to DOC if not found)
        $docTypeCode = 'DOC';
        if ($docType) {
            $docTypes = [
                'Invoice' => 'INV',
                'Purchase Order' => 'PO',
                'Delivery Order' => 'DO',
                'Contract' => 'CTR',
                'Proposal' => 'PRP',
                'Report' => 'RPT',
                'Memo' => 'MMO',
                'Agreement' => 'AGR',
                'Receipt' => 'RCT',
                'Manual Guide' => 'MGD',
                'Policy Document' => 'PLD',
                'Technical Specification' => 'TSP',
                'Meeting Minutes' => 'MMT',
                'Certification' => 'CRT',
                'Legal Document' => 'LGD'
            ];

            $docTypeCode = $docTypes[$docType] ?? 'DOC';
        }

        // Get the latest document number with this prefix
        $prefix = $docTypeCode . '-' . $day . '-' . $month . '-' . $year . '-';
        $lastDocument = TArchive::where('no_document', 'like', $prefix . '%')
            ->where('active_y_n', 'Y')
            ->orderBy('idrec', 'desc')
            ->first();


        $lastNumber = 0;
        if ($lastDocument) {
            $lastNumber = intval(substr($lastDocument->no_document, strlen($prefix)));
        }

        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $suggestedDocumentNumber = $prefix . $newNumber;

        return response()->json(['suggested_id_document' => $suggestedDocumentNumber]);
    }

    public function store(Request $request)
    {

        // Validasi input
        $validator = Validator::make($request->all(), [
            'date_modal' => 'required|date',
            'id_document' => 'required|string|max:255',
            'files' => 'required|array',
            'files.*' => 'file|mimes:pdf,jpg,jpeg|max:25000', // 25MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Mengambil file dari request
        $files = $request->file('files');
        $fileNames = [];
        $base64Data = [];

        foreach ($files as $file) {
            // Mendapatkan nama file
            $fileName = $file->getClientOriginalName();
            $fileNames[] = $fileName;

            // Membaca file sebagai string dan mengubahnya menjadi Base64
            $fileData = file_get_contents($file->getRealPath());
            $base64EncodedData = base64_encode($fileData);
            $base64Data[] = $base64EncodedData;
        }

        $pdfJpgData = implode(',', $base64Data);

        // Menyimpan data ke database
        try {
            DB::table('t_archive')->insert([
                'id_archieve' => 0,
                'date' => $request->input('date_modal'),
                'doc_type' => $request->input('type_docs_modal'),
                'no_document' => $request->input('id_document'),
                'sub_dep' => $request->input('sub_dep'),
                'description' => $request->input('description_modal'),
                'pdfblob' => $pdfJpgData,
                'file_name' => implode(',', $fileNames),
                'active_y_n' => 'Y',
                'created_by' => $request->input('user_email'),
                'updated_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Document added successfully!');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upload document: ' . $e->getMessage()
            ], 500);
        }
    }

    // EDIT
    public function indexEditDocument(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        $users = User::all();

        // Query untuk TArchive dengan relasi ajuDetails dan aju
        $archives = TArchive::with(['ajuDetails', 'aju'])
            ->where('active_y_n', 'Y');

        // Jika ada pencarian
        if ($search) {
            $archives->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(doc_type) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(file_name) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('aju', function ($query) use ($search) {
                        $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        $archives = $archives->paginate($perPage);

        $deps = MDepartment::getDepartments();
        $subDeps = MDepartment::getSubDepartments();
        $documentTypes = MDocumentType::where('status', 'active')->get();

        return view('pages.archive.document.index_edit', compact('archives', 'deps', 'subDeps', 'perPage', 'users', 'documentTypes'));
    }

    public function indexFormEdit($id_aju)
    {
        $archive = TArchive::where('id_aju', $id_aju)
            ->where('active_y_n', 'Y')
            ->get();

        $ajuDocs = TAju::where('id_aju', $id_aju)->first();

        return view('pages.archive.document.input.edit', compact('archive', 'ajuDocs'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'id_document' => 'required|string|max:255',
            'dep' => 'required|exists:m_department,id',
            'sub_dep' => 'required|exists:m_department,id',
            'type_docs_modal' => 'required|string',
            'description' => 'nullable|string',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:pdf,jpg,jpeg|max:25000', // 25MB max
            'user_email' => 'required|exists:users,id',
        ]);

        $archive = TArchive::findOrFail($id);

        // Update the archive data
        $archive->date = $validatedData['date'];
        $archive->no_document = $validatedData['id_document'];
        $archive->sub_dep = $validatedData['sub_dep'];
        $archive->doc_type = $validatedData['type_docs_modal'];
        $archive->description = $validatedData['description'];


        // Handle file upload if a new file is provided
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Convert file to base64
                $filePath = $file->store('uploads', 'public');
                $fileContent = Storage::disk('public')->get($filePath);
                $base64 = base64_encode($fileContent);

                // Save base64 string to the database
                $archive->pdfblob = $base64;
                $archive->file_name = $file->getClientOriginalName();
            }
        }

        $archive->save();

        return redirect()->route('index.editDocument')->with('success', 'Archive updated successfully!');
    }



    // DELETE
    public function indexDeleteDocument(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        // Query untuk TArchive dengan relasi ajuDetails dan aju
        $archives = TArchive::with(['ajuDetails', 'aju'])
            ->where('active_y_n', 'Y');

        // Jika ada pencarian
        if ($search) {
            $archives->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(doc_type) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(file_name) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('aju', function ($query) use ($search) {
                        $query->whereRaw('LOWER(no_docs) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        $archives = $archives->paginate($perPage);

        $deps = MDepartment::getDepartments();
        $subDeps = MDepartment::getSubDepartments();


        return view('pages.archive.document.index_delete', compact('archives', 'deps', 'subDeps', 'perPage'));
    }

    public function indexFormDelete($id)
    {

        $archive = TArchive::findOrFail($id);
        $archive->update(['active_y_n' => 'N']);
        $archive->updated_by = Auth::id();
        $archive->updated_at = now();

        return redirect()->route('index.DeleteDocument')
            ->with('success', 'Document has been deleted successfully.');
    }
}
