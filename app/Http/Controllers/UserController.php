<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Requests\User\StoreRequest;
use Illuminate\Database\Eloquent\Collection;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $classBlocks = User::distinct()->pluck('block');
        $coordinatorUser = User::where('role', 'coordinator')->get();
        $studentUser = User::where('role', 'student')->get();
        $itStudentCount = User::where('role', 'student')->where('course', 'Information Technology')->count();
        $isStudentCount = User::where('role', 'student')->where('course', 'Information System')->count();
        $csStudentCount = User::where('role', 'student')->where('course', 'Computer Science')->count();

        return Inertia::render('User/Index', [
            'itStudentCount' => $itStudentCount,
            'isStudentCount' => $isStudentCount,
            'csStudentCount' => $csStudentCount,
            'studentUser' => $studentUser,
            'coordinatorUser' => $coordinatorUser,
            'classBlocks' => $classBlocks,
        ]);
    }

    public function create()
    {
        return Inertia::render('User/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();

        User::create($validated);
        return redirect()->route('users.index')->with('message', 'User entry recorded successfully.');
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return Inertia::render('User/Create', [
            'user' => $user,
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
        return redirect()->route('users.index')->with('message', 'User updated successfully.');
    }

    public function destroy(int $id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('users.index')->with('message', 'User deleted successfully.');
    }


}


