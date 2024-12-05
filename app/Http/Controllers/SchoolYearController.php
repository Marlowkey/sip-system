<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use App\Http\Requests\SchoolYear\StoreRequest;

class SchoolYearController extends Controller
{
    public function index()
    {
        $schoolYears = SchoolYear::all(['id', 'year']);
        return Inertia::render('SchoolYear/Index', [
            'schoolYears' => $schoolYears,
        ]);
    }

    public function create()
    {
        return Inertia::render('SchoolYear/Create');
    }
    public function show(string $id)
    {
        $schoolYear = SchoolYear::with('users')->findOrFail($id);
        $classBlocks = User::whereNotNull('block')->distinct()->pluck('block');

        // Debugging

        return Inertia::render('SchoolYear/Show', [
            'schoolYear' => $schoolYear,
            'users' => $schoolYear->users,
            'classBlocks' => $classBlocks,
        ]);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'year' => 'required|string|max:9|unique:school_years,year', // Adjust the max length based on your requirements
        ]);

        SchoolYear::create($validatedData);

        return redirect()->route('schoolyears.index')->with('message', 'School year created successfully.');
    }

    public function destroy(int $id)
    {
        $schoolYear = SchoolYear::findOrFail($id);
        $schoolYear->delete();

        return redirect()->route('schoolyears.index')->with('message', 'School year deleted successfully.');
    }

    public function activate(SchoolYear $schoolYear)
    {
        $schoolYear->activate();

        return redirect()->back()->with('success', 'School year activated successfully.');
    }

    public function deactivate(SchoolYear $schoolYear)
    {
        $schoolYear->deactivate();

        return redirect()->back()->with('success', 'School year deactivated successfully.');
    }
}
