<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\HostTrainingEstablishment;
use Illuminate\Database\Eloquent\Collection;

class UserController extends Controller
{

    public function indexStudent(Request $request)
    {
        $request->validate([
            'school_year' => 'nullable|exists:school_years,id',
        ]);

        $schoolYear = $request->input('school_year');
        $studentUsers = User::where('role', 'student')
        ->when($schoolYear, function ($query) use ($schoolYear) {
            return $query->where('school_year_id', $schoolYear);
        })
        ->with('hostTrainingEstablishment') // Eager load the HostTrainingEstablishment relationship
        ->orderBy('last_name') // Optional: Order by last name
        ->get();
        $schoolYears = SchoolYear::all(['id', 'year']);
        $classBlocks = User::whereNotNull('block')->distinct()->pluck('block');
        $itStudentCount = User::where('role', 'student')->where('course', 'Information Technology')->count();
        $isStudentCount = User::where('role', 'student')->where('course', 'Information System')->count();
        $csStudentCount = User::where('role', 'student')->where('course', 'Computer Science')->count();

        return Inertia::render('User/Student/Index', [
            'itStudentCount' => $itStudentCount,
            'isStudentCount' => $isStudentCount,
            'csStudentCount' => $csStudentCount,
            'studentUser' => $studentUsers,
            'classBlocks' => $classBlocks,
            'schoolYears' => $schoolYears,
            'schoolYear' => $schoolYear,
        ]);
    }

    public function indexCoordinator(Request $request)
    {
        $request->validate([
            'school_year' => 'nullable|exists:school_years,id',
        ]);

        $schoolYear = $request->input('school_year');

        $coordinatorUsers = User::where('role', 'coordinator')
            ->when($schoolYear, function ($query) use ($schoolYear) {
                return $query->where('school_year_id', $schoolYear);
            })
            ->get();


        $schoolYears = SchoolYear::all(['id', 'year']);
        $classBlocks = User::distinct()->pluck('block');

        return Inertia::render('User/Coordinator/Index', [
            'coordinatorUser' => $coordinatorUsers,
            'classBlocks' => $classBlocks,
            'schoolYears' => $schoolYears,
            'schoolYear' => $schoolYear,
        ]);
    }

    public function indexStudentHte()
    {
        $user = auth()->user();
        $establishments = HostTrainingEstablishment::all();
        $studentUsers = User::where('role', 'student')
        ->where('course', $user->course)
        ->with('hostTrainingEstablishment')
        ->orderBy('last_name')
        ->get();

        $classBlocks = User::where('role', 'student')
        ->where('course', $user->course)
        ->distinct()
        ->pluck('block');

        return Inertia::render('User/StudentHte/Index', [
            'establishments' => $establishments,
            'user' => $user,
            'studentUsers' =>  $studentUsers,
            'classBlocks' => $classBlocks,
        ]);
    }

    public function create()
    {
        $htes = HostTrainingEstablishment::all();
        $schoolYears = SchoolYear::all(); // Assuming you have a SchoolYear model

        return Inertia::render('User/Create', [
            'htes' => $htes,
            'school_years' => $schoolYears // Pass the school years to the view
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        User::create($validated);
        return redirect()->back()->with('message', 'User created successfully.');
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $htes = HostTrainingEstablishment::all();

        return Inertia::render('User/Create', [
            'user' => $user,
            'htes' => $htes,
        ]);
    }

    public function update(UpdateRequest $request, int $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validated();

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password); // Hash the new password
        } else {
            unset($data['password']);
        }

        $user->fill($data);
        $user->save();
        return redirect()->back()->with('message', 'User updated successfully.');
    }

    public function destroy(int $id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('users-student.index')->with('message', 'User deleted successfully.');
    }

    public function updateHostTrainingEstablishment(Request $request, int $id)
{
    $request->validate([
        'host_training_establishment_id' => 'required|exists:host_training_establishments,id',
    ]);

    $user = User::findOrFail($id);
    $user->host_training_establishment_id = $request->input('host_training_establishment_id');
    $user->save();

    return redirect()->back()->with('message', 'Host Training Establishment updated successfully.');
}


}


