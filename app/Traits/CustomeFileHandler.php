<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait CustomeFileHandler
{
    public function uploadFile($path, $file)
    {
        $path = 'public/' . $path;
        return Storage::put($path, $file);
    }

    public function deleteFile($path)
    {
        Storage::delete('/' . $path);
    }
}
