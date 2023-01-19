<?php 
namespace App\Helpers;
use Illuminate\Support\Facades\Storage;

class ConfigHelper
{
    public static function Get()
    {
        $contents = Storage::get('uchip.config.json');
    }
}

?>