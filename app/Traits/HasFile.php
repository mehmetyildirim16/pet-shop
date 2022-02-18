<?php

namespace App\Traits;

use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HasFile
{

    public function addFile(mixed $file):void
    {
        Storage::disk('public')->putFileAs(
            'files',
            $file,
            $path = Str::random(10) . '.' . $file->getClientOriginalExtension()
        );
        File::create([
                         'uuid' => Str::uuid(),
                         'path' => $path,
                         'name' => $file->getClientOriginalName(),
                         'size' => $file->getClientSize(),
                         'type' => $file->getClientMimeType(),
                     ]);
        $this->metadata['image'] = $path;
        $this->save();
    }
}
