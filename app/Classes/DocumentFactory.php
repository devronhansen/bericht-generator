<?php

namespace App\Classes;

use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use App\anwendungsentwickler;


class DocumentFactory
{
    protected $templateProcessor;
    protected $fileName;
    protected $nr;
    protected $zip;
    protected $fileNames = [];
    protected $startWeek;
    protected $endWeek;
    protected $date;
    protected $year;
    protected $zipCreator;

    function __construct()
    {
        $this->zipCreator = new ZipCreator();
    }

    public function createDocumentsWith(array $demands)
    {
        $this->parseDemands($demands);
        $this->createEmptyDocumentsAndFill();

        return $this;
    }

    protected function parseDemands(array $demands)
    {
        $this->startWeek = Carbon::parse($demands['start'])->weekOfYear;
        $this->endWeek = Carbon::parse($demands['end'])->weekOfYear;
        $this->date = Carbon::createFromFormat('d.m.Y', $demands['start'])->startOfWeek();
        $this->nr = $demands['nr'];
        $this->year = $demands['year'];
    }

    protected function createEmptyDocumentsAndFill()
    {
        for ($currentWeek = $this->startWeek; $currentWeek <= $this->endWeek; $currentWeek++) {
            $this->templateProcessor = new TemplateProcessor(app_path().'/template.docx');
            $this->setValuesInDocument();
            $this->date = $this->date->addDay(3);
            $this->nr++;
            $this->templateProcessor->saveAs("files/" . $this->fileName);
            array_push($this->fileNames, $this->fileName);
        }
    }

    protected function setValuesInDocument()
    {
        $this->templateProcessor->setValue('jahr', $this->year);
        $this->templateProcessor->setValue('nr', $this->nr);
        $this->templateProcessor->setValue('name', 'Ron Hansen'); //Auth::user()->name
        $this->templateProcessor->setValue('start', $this->date->format('d.m.Y'));
        $date = $this->date->addDays(4);
        $this->templateProcessor->setValue('end', $date->format('d.m.Y'));
        $this->setJobValues();
        $this->fileName = "BerichtNr" . $this->nr . ".docx";

        return $this;
    }

    protected function setJobValues()
    {
        for ($i = 1; $i < env('JOB_COUNT'); $i++) {
            $job = anwendungsentwickler::where('id', mt_rand(1, 10))->first();
            $this->templateProcessor->setValue('job' . $i, $job->description);
        }

        return $this;
    }

    public function getZipFromDocuments()
    {
        $this->zipCreator->createZipFromDocuments($this->fileNames);
        return $this;
    }

    public function deleteDocuments(bool $permission)
    {
        if ($permission) {
            foreach ($this->fileNames as $fileName) {
                unlink("files/" . $fileName);
            }
        }
    }

    public function getZipPath()
    {
        return $this->zipCreator->getZipPatch();
    }
}