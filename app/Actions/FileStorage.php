<?php

namespace App\Actions;

use App\Enums\FileType;
use App\Models\File;
use Illuminate\Http\File as HttpFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileStorage
{
    public function upload(HttpFile|UploadedFile|string $file, $id): File
    {
        $md5 = md5_file($file);

        if ($existingFile = File::where('md5', $md5)->first()) {
            return File::create([
                'name' => $existingFile->name,
                'path' => $existingFile->path,
                'size' => $existingFile->size,
                'mime_type' => $existingFile->mime_type,
                'md5' => $existingFile->md5,
                'type' => $existingFile->type,
                'project_id' => $id,
            ]);
        }

        $mimeType = $file->getMimeType();
        $size = $file->getSize();

        if (strpos($mimeType, 'video') === 0) {
            $type = FileType::VIDEO;
        } else if (strpos($mimeType, 'image') === 0) {
            $type = FileType::IMAGE;
        } else {
            throw new \Exception("Unsupported MIME Type: $mimeType");
        }

        $path = Storage::putFile('', $file, 'public');

        return File::create([
            'name' => last(explode('/', $path)),
            'path' => $path,
            'size' => $size,
            'mime_type' => $mimeType,
            'md5' => $md5,
            'type' => $type,
            'project_id' => $id,
        ]);
    }
}
