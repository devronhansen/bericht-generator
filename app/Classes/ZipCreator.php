<?php

namespace App\Classes;

use PhpOffice\PhpWord\Shared\ZipArchive;

class ZipCreator
{
    protected $zip;
    const ZIP_RESULT = 'result.zip';

    public function createZipFromDocuments(array $fileNames)
    {
        $this->openZipArchive();
        $this->addAllFiles($fileNames);
        $this->closeZipArchive();
    }

    protected function openZipArchive()
    {
        $this->zip = new ZipArchive();
        $this->zip->open('result.zip', ZipArchive::CREATE);
    }

    protected function addAllFiles($fileNames)
    {
        foreach ($fileNames as $fileName) {
            $this->zip->addFile("files/" . basename($fileName), $fileName);
        }
    }

    protected function closeZipArchive()
    {
        $this->zip->close();
    }

    public function getZipPatch()
    {
        return self::ZIP_RESULT;
    }
}