<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Services\DocumentService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Document\StoreRequest;

class DocumentController extends Controller
{
    protected Document $document;
    protected DocumentService $documentService;

    public function __construct(Document $document, DocumentService $documentService)
    {
        $this->document = $document;
        $this->documentService = $documentService;
    }

    public function index()
    {
        $user = auth()->user();
        $documents = Document::getDocumentsForUser($user->id);
        $documentWithNumberOfCompleted = $this->document->getDocumentsWithNumberOfCompleted($user);

        return Inertia::render('Document/Index', [
            'user' => $user,
            'documents' => $documents,
            'documentWithNumberOfCompleted' => $documentWithNumberOfCompleted,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Document/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $this->documentService->storeDocument($validated);

        return redirect()->route('documents.index')->with('success', 'Document created successfully.');
    }

    public function show(string $id)
    {
        $user = auth()->user();
        $classBlocks = User::whereNotNull('block')->distinct()->pluck('block');
        $document = Document::with([
            'users' => function ($query) {
                $query->withPivot('file_path', 'is_completed');
            }
        ])->findOrFail($id);

        $studentsWithFilePath = $document->users->map(function ($user) {
            return [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'block' => $user->block,
                'file_path' => $user->pivot->file_path,
                'is_completed' => $user->pivot->is_completed,
            ];
        });

        return Inertia::render('Document/Show', [
            'user' => $user,
            'document' => $document,
            'students' => $studentsWithFilePath,
            'classBlocks' => $classBlocks,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $document = Document::findOrFail($id);

        return Inertia::render('Document/Create', [
            'document' => $document,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRequest $request, $id)
    {
        $document = Document::findOrFail($id);
        $validated = $request->validated();
        $this->documentService->updateDocument($document, $validated);

        return Redirect::route('documents.index')->with('message', 'Document updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        $this->documentService->deleteDocument($document);
        return Redirect::route('documents.index')->with('message', 'Document deleted successfully.');
    }

    public function download(Document $document)
    {
        $filePath = $this->documentService->getUploadFilePath($document);
        $fileName = $this->documentService->getUploadFileName($document, $filePath);

        return response()->download($filePath, $fileName);
    }

    public function uploadDocument(Request $request, Document $document)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:5048',
        ]);

        $filePath = $request->file('file')->store('uploads/documents', 'public');

        auth()->user()->documents()->syncWithoutDetaching([
            $document->id => [
                'file_path' => $filePath,
                'is_completed' => true,
            ],
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }


    public function downloadDocument($documentId, $userId)
    {
        $document = Document::findOrFail($documentId);

        $user = $document->users()->findOrFail($userId);

        if ($user->pivot && $user->pivot->file_path) {
            $filePath = $user->pivot->file_path;

            if (Storage::disk('public')->exists($filePath)) {
                $documentName = $document->title;
                $userName = $user->first_name . ' ' . $user->last_name;
                $customFileName = $userName . ' - ' . $documentName . '.' . pathinfo($filePath, PATHINFO_EXTENSION);

                return response()->download(storage_path("app/public/{$filePath}"), $customFileName, [
                    'Content-Type' => 'application/octet-stream',
                    'Content-Disposition' => 'attachment; filename="' . $customFileName . '"'
                ]);            } else {
                return back()->with('error', 'File not found.');
            }
        }

        return back()->with('error', 'No file uploaded for this document.');
    }
}
