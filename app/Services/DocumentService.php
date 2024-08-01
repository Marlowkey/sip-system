<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentService
{

    public function getUploadFilePath ($document)
    {
        return public_path('storage' . $document->file_path); // Get public path
    }

    public function getUploadFileName ($document, $filePath = null)
    {
        if (file_exists($filePath)) {
            // Get the file extension
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);

            // Set the downloaded file name with extension
            $fileName = $document->title . '.' . $extension;
            return $fileName;
        }
    }

    
    public function storeDocument(array $validatedData)
    {
        $document = new Document();
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

    public function deleteDocument(Document $document)
    {
        if ($document->file_path) {
            Storage::delete('public/' . $document->file_path);
        }
        $document->delete();
    }
}
