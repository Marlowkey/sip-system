<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Inertia\Inertia;
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

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'year' => 'required|string|max:9|unique:school_years,year', // Adjust the max length based on your requirements
        ]);

        SchoolYear::create(  $validatedData);

        return redirect()->route('schoolyears.index')->with('message', 'School year created successfully.');
    }


    public function destroy(int $id)
    {
        $schoolYear = SchoolYear::findOrFail($id);
        $schoolYear->delete();

        return redirect()->route('schoolyears.index')->with('message', 'School year deleted successfully.');
    }
}
