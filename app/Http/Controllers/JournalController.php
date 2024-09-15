<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\JournalService;
use Inertia\Inertia;
use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Journal\StoreRequest;

class JournalController extends Controller
{

    protected Journal $journal;
    protected JournalService $journalService;

    public function __construct(Journal $journal, JournalService $journalService)
    {
        $this->journal = $journal;
        $this->journalService = $journalService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        return Inertia::render('Journal/Index', [
            'user' => $user,
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
        $this->journalService->storeDocument($validated);
        return redirect()->route('journals.index')->with('message', 'Journal entry recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $journal = Journal::findOrFail($id);
        return Inertia::render('Journal/Show', [
            'journal' => $journal,
        ]);
    }

    /**
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
        $journal->update($request->validated());
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


}
