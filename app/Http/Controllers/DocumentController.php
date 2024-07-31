<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Document;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use  App\Http\Requests\Document\StoreRequest;


class DocumentController extends Controller
{
    protected Document $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
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

        $document = new Document();
        $document->title = $validated['title'];
        $document->due_date = $validated['due_date'];
        $document->description = $validated['description'];

        if ($validated['file']) {
            $file = $validated['file'];
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/documents', $fileName);
            $document->file_path = 'documents/' . $fileName;
        }
        $document->save();

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
        $validated = $request->validated();

        $document = new Document();
        $document->title = $validated['title'];
        $document->due_date = $validated['due_date'];
        $document->description = $validated['description'];

        if ($validated['file']) {
            $file = $validated['file'];
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/documents', $fileName);
            $document->file_path = 'documents/' . $fileName;
        }
        $document->save();
        
        return Redirect::route('documents.index')->with('message', 'Document updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        // Delete file if it exists
        if ($document->file_path) {
            Storage::delete('public/' . $document->file_path);
        }
        $document->delete();
        return Redirect::route('documents.index')->with('message', 'Document deleted successfully.');
    }

    public function download(Document $document)
    {
        $filePath = public_path('storage/' . $document->file_path); // Get public path

        if (file_exists($filePath)) {
            // Get the file extension
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);

            // Set the downloaded file name with extension
            $fileName = $document->title . '.' . $extension;

            // Return the file as a downloadable response
            return response()->download($filePath, $fileName);
        }
    }
}
