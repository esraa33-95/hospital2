<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait Common
{
    public function uploadFile($file, $path, $oldFile = null)
    {
        
        if ($oldFile && File::exists($path . '/' . $oldFile)) {
            File::delete($path . '/' . $oldFile);
        }

    
        $file_extension = $file->getClientOriginalExtension();
        $file_name = time() . '.' . $file_extension;
        $file->move(public_path($path), $file_name);
    
        
        return url($path . '/' . $file_name);
       
    }
}
