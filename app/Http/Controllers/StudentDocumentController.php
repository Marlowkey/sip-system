<?php

namespace App\Http\Controllers;

use App\Http\Requests\Document\StudentDocumentUpdateRequest;
use Illuminate\Http\Request;
use App\Models\StudentDocument;

class StudentDocumentController extends Controller
{
    public function updateCompletionStatus(StudentDocumentUpdateRequest $request)
    {
        $validated = $request->validated();

        $studentDocument = StudentDocument::updateOrCreate(
            [
                'user_id' => $validated['user_id'],
                'document_id' => $validated['document_id'],
            ],
            [
                'is_completed' => $validated['is_completed'],
            ]
        );

        return redirect()->back()->with('message', 'Document completion status updated successfully.');
    }
}

