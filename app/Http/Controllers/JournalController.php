<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Journal\StoreRequest;

class JournalController extends Controller
{

    protected Journal $journal;

    public function __construct(Journal $journal)
    {
        $this->journal = $journal;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'class_block' => 'nullable|string',
        ]);

        $classBlock = $request->class_block ?? null;
        $user = auth()->user();


        $journal = $this->journal->getJournalsForUser($user);
        $students = $this->journal->getStudentUserJournalsForCoordinator($user, $classBlock);
        $classBlocks = User::distinct()->pluck('block');

        return Inertia::render('Journal/Index', [
            'user' => $user,
            'journal' => $journal,
            'classBlocks' => $classBlocks,
            'classBlock' => $classBlock,
            'students' => $students,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        return Inertia::render('Journal/Create', [
            'user' => $user,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image_path')) {
            $image_path = $request->file('image_path')->store('journals', 'public');
            $validated['image_path'] = $image_path;
        }

        Auth::user()->journals()->create($validated);
        return redirect()->route('journals.index')->with('message', 'Journal entry recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = auth()->user();
        $journal = Journal::with('user')->findOrFail($id); // Eager load the user relationship

        $username = $journal->user ? $journal->user->first_name . ' ' . $journal->user->last_name : 'Unknown User';

        return Inertia::render('Journal/Show', [
            'journal' => $journal,
            'username' => $username,
            'user' => $user,
        ]);
    }

    /**show
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $journal = Journal::findOrFail($id);
        return Inertia::render('Journal/Create', [
            'journal' => $journal,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRequest $request, int $id)
    {
        $journal = Journal::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('image_path')) {
            $image_path = $request->file('image_path')->store('journals', 'public');
            $data['image_path'] = $image_path;
        } else {
            unset($data['image_path']);
        }

        $journal->update($data);

        return redirect()->route('journals.index')->with('message', 'Journal updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        Journal::findOrFail($id)->delete();

        return redirect()->route('journals.index')->with('message', 'Journal deleted successfully.');
    }


    public function markAsReviewed(Request $request, int $id)
    {
        $journal = Journal::findOrFail($id);
        $journal->reviewed = true;
        $journal->save();

        return redirect()->route('journals.index')->with('message', 'Journal marked as reviewed successfully.');
    }

    public function addFeedback(Request $request, int $id)
    {
        $request->validate([
            'feedback' => 'required|string|max:500',
        ]);

        $journal = Journal::findOrFail($id);
        $journal->feedback = $request->feedback;
        $journal->save();

        return redirect()->route('journals.index')->with('message', 'Feedback added successfully.');
    }




}
