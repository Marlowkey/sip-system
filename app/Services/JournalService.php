<?php

namespace App\Services;

use App\Models\Journal;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class JournalService
{

    protected Journal $journal;


    public function __construct(Journal $journal)
    {
        $this->journal = $journal;
    }


    public function getUploadFilePath ($journal): string
    {
        return public_path('storage' . $journal->file_path); // Get public path
    }


    public function getUploadFileName ($journal, $filePath = null)
    {
        if (file_exists($filePath)) {
            // Get the file extension
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);

            // Set the downloaded file name with extension
            $fileName = $journal->title . '.' . $extension;
            return $fileName;
        }
    }


    public function storeDocument(array $validatedData)
    {
        $this->journal->user_id = auth()->id();
        $this->journal->title = $validatedData['title'];
        $this->journal->date = $validatedData['date'];
        $this->journal->week = $validatedData['week'];
        $this->journal->content = $validatedData['content'];

        if (isset($validatedData['image_path']) && $validatedData['image_path'] instanceof UploadedFile) {
            $file = $validatedData['image_path'];
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('journals', $fileName);
            $this->journal->image_path = '\journals\\' . $fileName;
        }
        $this->journal->save();
        return $this->journal;
    }

    public function updateDocument(Document $document, array $validatedData)
    {
        $document->title = $validatedData['title'];
        $document->due_date = $validatedData['due_date'];
        $document->description = $validatedData['description'];

        if (isset($validatedData['file']) && $validatedData['file'] instanceof UploadedFile) {
            $file = $validatedData['file'];
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('documents', $fileName);
            $document->file_path = '\documents\\' . $fileName;
        }
        $document->save();
        return $document;
    }

    public function deleteDocument(Journal $journal)
    {
        if ($journal->image_path) {
            Storage::delete('public/' . $journal->image_path);
        }
        $journal->delete();
    }
}
