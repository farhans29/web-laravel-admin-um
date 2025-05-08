<?php

namespace App\Http\Controllers\Master\DocumentType;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\MDocumentType;
use Illuminate\Support\Facades\Auth;

class DocumentTypeController extends Controller
{
    public function index(Request $request)
    {

        $validated = $request->validate([
            'per_page' => 'sometimes|integer|min:1|max:100',
            'search' => 'sometimes|string|max:255'
        ]);

        $perPage = $validated['per_page'] ?? 5;
        $search = $validated['search'] ?? null;
        $query = MDocumentType::with(['createdByUser' => function ($query) {
            $query->select('id', 'name');
        }]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $query->orderBy('created_at', 'asc');

        $documentTypes = $query->paginate($perPage)
            ->appends($request->except('page'));

        return view('pages.master.m_document.index_list', [
            'documentTypes' => $documentTypes,
            'perPage' => $perPage,
            'search' => $search
        ]);
    }

    public function indexNew(Request $request)
    {
        $validated = $request->validate([
            'per_page' => 'sometimes|integer|min:1|max:100',
            'search' => 'sometimes|string|max:255'
        ]);

        $perPage = $validated['per_page'] ?? 5;
        $search = $validated['search'] ?? null;
        $query = MDocumentType::with(['createdByUser' => function ($query) {
            $query->select('id', 'name');
        }]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $query->orderBy('created_at', 'asc');

        $documentTypes = $query->paginate($perPage)
            ->appends($request->except('page'));

        return view('pages.master.m_document.index_new', [
            'documentTypes' => $documentTypes,
            'perPage' => $perPage,
            'search' => $search
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:m_document_types,code',
            'name' => 'required|string|max:255',
        ]);

        $documentType = new MDocumentType();
        $documentType->code = $validated['code'];
        $documentType->name = $validated['name'];
        $documentType->status = 'Active';
        $documentType->created_by = Auth::id();
        $documentType->save();

        return redirect()->route('indexNew.documentType')
            ->with('success', 'Document type created successfully');
    }

    public function indexEdit(Request $request)
    {
        $validated = $request->validate([
            'per_page' => 'sometimes|integer|min:1|max:100',
            'search' => 'sometimes|string|max:255'
        ]);

        $perPage = $validated['per_page'] ?? 5;
        $search = $validated['search'] ?? null;
        $query = MDocumentType::with(['createdByUser' => function ($query) {
            $query->select('id', 'name');
        }]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $query->orderBy('created_at', 'asc');

        $documentTypes = $query->paginate($perPage)
            ->appends($request->except('page'));

        return view('pages.master.m_document.index_edit', [
            'documentTypes' => $documentTypes,
            'perPage' => $perPage,
            'search' => $search
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:100',
        ]);

        $documentType = MDocumentType::findOrFail($id);
        $documentType->update([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'status' => 'Active',
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('indexEdit.documentType')->with('success', 'Document type updated successfully');
    }

    public function indexDelete(Request $request)
    {
        $validated = $request->validate([
            'per_page' => 'sometimes|integer|min:1|max:100',
            'search' => 'sometimes|string|max:255'
        ]);

        $perPage = $validated['per_page'] ?? 5;
        $search = $validated['search'] ?? null;
        $query = MDocumentType::with(['createdByUser' => function ($query) {
            $query->select('id', 'name');
        }]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $query->orderBy('created_at', 'asc');

        $documentTypes = $query->paginate($perPage)
            ->appends($request->except('page'));

        return view('pages.master.m_document.index_delete', [
            'documentTypes' => $documentTypes,
            'perPage' => $perPage,
            'search' => $search
        ]);
    }

    public function softDelete($id)
    {
        try {
            $documentType = MDocumentType::findOrFail($id);
            $documentType->update([
                'status' => 'Inactive',
                'updated_by' => Auth::id(), // or Auth::id()
                'updated_at' => now()
            ]);

            return redirect()->route('indexDelete.documentType')->with('success', 'Document type deactivated successfully');
        } catch (\Exception $e) {
            return redirect()->route('indexDelete.documentType')->with('error', 'Failed to deactivate document type');
        }
    }
}
