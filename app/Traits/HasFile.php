<?php

namespace App\Traits;

use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DB;

trait HasFile
{

    public function addFile(mixed $file): void
    {
        DB::transaction(function () use ($file) {
            //delete if exists
            if (isset($this->metadata['image'])) {
                Storage::disk('public')->delete('files/'.$this->metadata['image']);
            }
            //add new
            Storage::disk('public')->putFileAs(
                'files',
                $file,
                $path = Str::random(10) . '.' . $file->getClientOriginalExtension()
            );
            File::create([
                             'uuid' => Str::uuid(),
                             'path' => $path,
                             'name' => $file->getClientOriginalName(),
                             'size' => $file->getSize(),
                             'type' => $file->getClientMimeType(),
                         ]);
            $metadata = $this->metadata;
            $metadata['image'] = $path;
            $this->metadata = $metadata;
            $this->save();
        });
    }
}
