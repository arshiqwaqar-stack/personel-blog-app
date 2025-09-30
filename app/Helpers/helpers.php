<?php

use Illuminate\Support\Facades\Storage;

 function storeImage($folder,$image){
        $folder = str_replace(" ","_",$folder);
        $path = Storage::disk("articles")->put($folder, $image);
        return $path;
    }