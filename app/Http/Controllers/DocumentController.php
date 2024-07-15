<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Models\StudentDocument;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;


class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $documentAll = Document::all();


        // Fetch documents with users and their completion status
        $documents = Document::with(['users' => function ($query) {
            $query->where('user_id', auth()->id());
        }])->get();


        $documentWithNumberOfCompleted = $documentAll->map(function ($document) {
            $studentUsers = User::where('role', 'student')
                ->count();

            $completedDocuments = $document->users->filter(function ($user) {
                return $user->pivot->is_completed;
            })->count();

            return [
                'id' => $document->id,
                'title' => $document->title,
                'description' => $document->description,
                'file_path' => $document->file_path,
                'due_date' => $document->due_date,
                'completed' => $completedDocuments,
                'number_of_users' => $studentUsers,
            ];
        });

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
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'description' => 'nullable|string',
            'file' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $document = new Document();
        $document->title = $request->title;
        $document->due_date = $request->due_date;
        $document->description = $request->description;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
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
    public function update(Request $request, $id)
    {
        // Validate the incoming request
         $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'description' => 'nullable|string',
            'file' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $document = Document::findOrFail($id);
        $document->title = $request->title;
        $document->due_date = $request->due_date;
        $document->description = $request->description;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
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
