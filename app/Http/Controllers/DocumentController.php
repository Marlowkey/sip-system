<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Document;
use App\Models\StudentDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
    // Validate the incoming request
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'due_date' => 'required|date',
        'description' => 'nullable|string',
    ]);

    // Create a new document with the validated data
        Document::create($validated);
        return Redirect::route('documents.index')->with('message', 'Document created successfully.');

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
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'due_date' => 'required|date',
        'description' => 'nullable|string',
    ]);

    $document = Document::findOrFail($id);
    $document::update($validated);
    return Redirect::route('documents.index')->with('message', 'Document updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        $document->delete();
        return Redirect::route('documents.index')->with('message', 'Document deleted successfully.');
    }
}
