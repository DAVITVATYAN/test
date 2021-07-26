<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ProcessImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:image {folder?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->info('Starting create images!');

            $folderNumber = $this->argument('folder') ?: 'output-images';
            $images = File::allFiles(public_path('img/source-images'));
            foreach ($images as $image) {
                $authorName = $this->getAuthorName($image->getFilename());
                $this->saveImagesInPath($image->getFilename(),$authorName,$folderNumber);
            }
            $this->info('Folder created successfully!');

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * @param $fileName
     * @return string
     */
    private function getAuthorName($fileName) {
        //het file name except extention
        $authorFullName = substr($fileName, 0, strpos($fileName, '.')) ;
        // Change '-' simbol to ' '
        $authorName = ucwords(str_replace('-', ' ', $authorFullName));
        return $authorName;
    }

    /**
     * @param $fileName
     * @param $authorName
     * @param $folderNumber
     */
    private function saveImagesInPath($fileName, $authorName, $folderNumber) {
        $img = Image::make(public_path('img/source-images/' .$fileName));
        //get img sizes
        $height = $img->height();
        $width = $img->width();
        //make directory which name we enter in console
        File::makeDirectory(public_path('img/' . $folderNumber), 0777, true, true);
        $img->text($authorName, $width - 100, $height - 30, function($font) {
            $font->file(public_path('OpenSans.ttf'));
            $font->size(24);
            $font->color('#fff');
            $font->align('center');
            $font->valign('center');
            $font->angle(0);
        });
        // save images in folder
        $img->save(public_path('img/'. $folderNumber . '/' . $fileName));
    }
}
