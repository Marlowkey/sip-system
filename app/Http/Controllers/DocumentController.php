<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Document;
use App\Services\DocumentService;
use Illuminate\Support\Facades\Redirect;
use  App\Http\Requests\Document\StoreRequest;

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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
}
