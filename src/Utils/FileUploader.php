<?php

namespace App\Utils;

use App\Utils\Interfaces\UploaderInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class FileUploader implements UploaderInterface
{

    private $targetDirectory;

    public $file;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $originalFilename;
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }
/* 

    public function upload($file)
    {
        $file_number = random_int(1, 100000000);

        $fileName = $file_number . '.' . $file->guessExtension();

        try {
            
            $file->move($this->getTargetDirectory(), $fileName);

        } catch (FileException $ex) {
            
        }

        $original_file_name = $this->clear(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));

        return [$fileName, $original_file_name];    
    } */

    private function clear($string)
    {
        $string = \preg_replace('/[^A-Za-z0-9- ]+/', '', $string);

        return $string;
    }

    public function delete($path)
    {
        $fileSystem = new Filesystem();

        try {
            $fileSystem->remove('.' . $path);
        } catch (IOExceptionInterface $ex) {
            echo "an error occured while deleting your file at" . $ex->getPath();
        }

        return true;
    }
}