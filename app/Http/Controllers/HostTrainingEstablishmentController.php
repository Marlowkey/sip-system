<?php

namespace App\Http\Controllers;

use App\Models\HostTrainingEstablishment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HostTrainingEstablishmentController extends Controller
{

    public function index()
    {
        $establishments = HostTrainingEstablishment::all();
        return Inertia::render('HostTrainingEstablishment/Index', [
            'establishments' => $establishments,
        ]);
    }

    public function create()
    {
        return Inertia::render('HostTrainingEstablishment/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        HostTrainingEstablishment::create($validated);
        return redirect()->route('htes.index')->with('message', 'Establishment created successfully.');
    }

    public function show($id)
    {
        $establishment = HostTrainingEstablishment::findOrFail($id);
        return Inertia::render('HostTrainingEstablishment/Show', [
            'establishment' => $establishment,
        ]);
    }

    public function edit($id)
    {
        $establishment = HostTrainingEstablishment::findOrFail($id);
        return Inertia::render('HostTrainingEstablishment/Create', [
            'establishment' => $establishment,
        ]);
    }

    public function update(Request $request, $id)
    {
        $establishment = HostTrainingEstablishment::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $establishment->update($validated);
        return redirect()->route('htes.index')->with('message', 'Establishment updated successfully.');
    }

    public function destroy($id)
    {
        HostTrainingEstablishment::findOrFail($id)->delete();
        return redirect()->route('htes.index')->with('message', 'Establishment deleted successfully.');
    }
}
