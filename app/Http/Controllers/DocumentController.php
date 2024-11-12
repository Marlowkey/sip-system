<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Services\DocumentService;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
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
        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf|max:2048',
        ]);

        $user = $request->user();

        $existingDocument = $user->documents()->where('document_id', $document->id)->first();

        if ($existingDocument && $existingDocument->pivot->file_path) {
            $existingFilePath = public_path('storage/' . $existingDocument->pivot->file_path);

            if (file_exists($existingFilePath)) {
                unlink($existingFilePath);
            }
        }

        $filePath = $request->file('file')->store('document-uploads', 'public');

        $user->documents()->syncWithoutDetaching([
            $document->id => [
                'file_path' => $filePath,
            ],
        ]);

        // Return success message
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

                $extension = pathinfo($filePath, PATHINFO_EXTENSION);

                $customFileName = $userName . ' - ' . $documentName . '.' . $extension;

                $mimeType = $this->getMimeType($extension);

                return Storage::disk('public')->download($filePath, $customFileName);
            } else {
                return back()->with('error', 'File not found.');
            }
        }
        return back()->with('error', 'No file uploaded for this document.');
    }

    private function getMimeType($extension)
    {
        $mimeTypes = [
            'pdf'  => 'application/pdf',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'webp' => 'image/webp',
        ];

        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }
}
