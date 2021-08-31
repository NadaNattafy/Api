<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;


    function getImg($image)
    {
        if (is_null($image)) {
            return '';
        }
        return url('/') . '/storage/' . $image;
    }
    function uploadImage($file, $dir = 'Pics')
    {
       return 'storage/'. \Storage::disk('public')->putFile($dir, $file);    }


?>
