<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\HostTrainingEstablishment;

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
            'moa_file_path' => 'nullable|file|mimes:pdf|max:2048',
            'moa_validity_period' => 'nullable|date',
        ]);

        if ($request->hasFile('moa_file_path')) {
            $validated['moa_file_path'] = $request->file('moa_file_path')->store('moa_files', 'public');
        }

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
            'moa_file' => 'nullable|file|mimes:pdf|max:2048',
            'moa_validity_period' => 'nullable|date',
        ]);

        if ($request->hasFile('moa_file')) {
            if ($establishment->moa_file_path) {
                Storage::disk('public')->delete($establishment->moa_file_path);
            }
            $validated['moa_file_path'] = $request->file('moa_file')->store('moa_files', 'public');
        }

        $establishment->update($validated);
        return redirect()->route('htes.index')->with('message', 'Establishment updated successfully.');
    }
    public function destroy($id)
    {
        $establishment = HostTrainingEstablishment::findOrFail($id);
        if ($establishment->moa_file_path) {
            Storage::disk('public')->delete($establishment->moa_file_path);
        }
        $establishment->delete();
        return redirect()->route('htes.index')->with('message', 'Establishment deleted successfully.');
    }

    public function downloadMoa($id)
    {
        $establishment = HostTrainingEstablishment::findOrFail($id);

        if (!$establishment->moa_file_path || !Storage::disk('public')->exists($establishment->moa_file_path)) {
            return redirect()->back()->with('error', 'MOA file not found.');
        }

        return Storage::disk('public')->download($establishment->moa_file_path);
    }

    public function updateMoaFile(Request $request, $id)
    {
        $establishment = HostTrainingEstablishment::findOrFail($id);

        $request->validate([
            'moa_file_path' => 'required|file|mimes:pdf|max:2048',
        ]);

        $path = $request->file('moa_file_path')->store('moa_files', 'public');

        $establishment->update(['moa_file_path' => $path]);

        return redirect()->route('htes.index')->with('message', 'MOA file updated successfully.');
    }
}
