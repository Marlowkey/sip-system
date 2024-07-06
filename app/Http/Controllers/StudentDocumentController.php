<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentDocument;

class StudentDocumentController extends Controller
{
    public function updateCompletionStatus(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'document_id' => 'required|exists:documents,id',
            'is_completed' => 'required|boolean',
        ]);

        $studentDocument = StudentDocument::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'document_id' => $request->document_id,
            ],
            [
                'is_completed' => $request->is_completed,
            ]
        );
        return redirect()->back()->with('message', 'Document completion status updated successfully.');
    }
}
