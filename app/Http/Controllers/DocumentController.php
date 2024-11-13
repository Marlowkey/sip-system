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
    public function create()
    {
        return Inertia::render('Document/Create');
    }
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('file_path')) {
            $title = $validated['title'];
            $extension = $request->file('file_path')->getClientOriginalExtension();

            $validated['file_path'] = $request->file('file_path')->storeAs(
                'documents',
                $title . '.' . $extension,
                'public'
            );
        }

        Document::create($validated);
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


    public function edit($id)
    {
        $document = Document::findOrFail($id);

        return Inertia::render('Document/Create', [
            'document' => $document,
        ]);
    }

    public function update(StoreRequest $request, $id)
    {
        $document = Document::findOrFail($id);
        $validated = $request->validated();
        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file')->store('documents', 'public');
        }

        $document->update($validated);

        return Redirect::route('documents.index')->with('message', 'Document updated successfully.');
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }
        $document->delete();
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

        // Get the student's name and document title
        $studentName = $user->last_name . ', ' .  $user->first_name;  // Assuming the user's name is stored in the 'name' attribute
        $documentTitle = $document->title;
        $extension = $request->file('file')->getClientOriginalExtension();

        // Create a filename with both student name and document title
        $filename = $studentName . '_' . $documentTitle . '.' . $extension;

        $filePath = $request->file('file')->storeAs(
            'document-uploads',
            $filename,
            'public'
        );

        $user->documents()->syncWithoutDetaching([
            $document->id => [
                'file_path' => $filePath,
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
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                $customFileName = $userName . ' - ' . $documentName . '.' . $extension;

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

    public function updateFile(Request $request, $id)
    {
        $document = Document::findOrFail($id);
        $request->validate([
            'file_path' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);
        if ($request->hasFile('file_path')) {
            $title = $document->title;
            $extension = $request->file('file_path')->getClientOriginalExtension();
            $path = $request->file('file_path')->storeAs(
                'documents',
                $title . '.' . $extension,
                'public'
            );
            $document->update(['file_path' => $path]);
        }
        return Redirect::route('documents.index')->with('message', 'Document updated successfully.');
    }
}
