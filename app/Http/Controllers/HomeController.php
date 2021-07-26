<?php


namespace App\Http\Controllers;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
class HomeController
{
    public function index() {
        $files = File::allFiles(public_path('img'));
        $images = [];
        foreach ($files as $file) {
            $images[$file->getRelativePath()][] = 'img/' . $file->getRelativePath() . '/' . $file->getFilename();
        }
        return view('welcome', ['images' => $images]);
    }
}
