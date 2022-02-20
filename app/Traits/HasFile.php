<?php

namespace App\Traits;

use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DB;

trait HasFile
{

    public static function bootHasFile()
    {
        static::deleting(function ($model) {
            if (isset($model->metadata['image'])) {
                $file = File::whereUuid($model->metadata['image'])->firstOrFail();
                Storage::disk('public')->delete('pet-shop/'.$file->path);
            }
        });
    }

    public function addFile(mixed $file): void
    {
        DB::transaction(function () use ($file) {
            //delete if exists
            if (isset($this->metadata['image'])) {
                $file = File::whereUuid($this->metadata['image'])->firstOrFail();
                Storage::disk('public')->delete('pet-shop/'.$file->path);
            }
            //add new
            Storage::disk('public')->putFileAs(
                'pet-shop',
                $file,
                $path = Str::random(10) . '.' . $file->getClientOriginalExtension()
            );
            $file = File::create([
                             'uuid' => Str::uuid(),
                             'path' => $path,
                             'name' => $file->getClientOriginalName(),
                             'size' => $file->getSize(),
                             'type' => $file->getClientMimeType(),
                         ]);
            $metadata = $this->metadata;
            $metadata['image'] = $file->uuid;
            $this->metadata = $metadata;
            $this->save();
        });
    }
}
