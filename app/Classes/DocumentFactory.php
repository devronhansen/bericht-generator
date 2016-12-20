<?php

namespace App\Classes;

use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use App\anwendungsentwickler;
use Illuminate\Support\Facades\Auth;

class DocumentFactory
{
    protected $templateProcessor;
    protected $fileNames = [];
    protected $startWeek;
    protected $endWeek;
    protected $date;
    protected $year;
    protected $nr;

    public function createDocumentsWith(array $demands)
    {
        $this->parseDemands($demands);
        $this->prepareDocument();

        return $this->fileNames;
    }

    protected function parseDemands(array $demands)
    {
        $this->startWeek = Carbon::parse($demands['start'])->weekOfYear;
        $this->endWeek = Carbon::parse($demands['end'])->weekOfYear;
        $this->date = Carbon::createFromFormat('d.m.Y', $demands['start'])->startOfWeek();
        $this->nr = $demands['nr'];
        $this->year = $demands['year'];
    }

    protected function prepareDocument()
    {
        for ($currentWeek = $this->startWeek; $currentWeek <= $this->endWeek; $currentWeek++) {
            $this->templateProcessor = new TemplateProcessor(app_path() . '/template.docx');
            $fileName = $this->setValuesInDocument();
            $this->date = $this->date->addDay(3);
            $this->nr++;
            $this->templateProcessor->saveAs("files/" . $fileName);
            array_push($this->fileNames, $fileName);
        }
    }

    protected function setValuesInDocument()
    {
        $this->templateProcessor->setValue('jahr', $this->year);
        $this->templateProcessor->setValue('nr', $this->nr);
        $this->templateProcessor->setValue('name', Auth::user()->name);
        $this->templateProcessor->setValue('start', $this->date->format('d.m.Y'));
        $date = $this->date->addDays(4);
        $this->templateProcessor->setValue('end', $date->format('d.m.Y'));
        $this->setJobValues();
        $fileName = "BerichtNr" . $this->nr . ".docx";

        return $fileName;
    }

    protected function setJobValues()
    {
        for ($i = 1; $i < 16; $i++) {
            $job = anwendungsentwickler::where('id', mt_rand(1, 10))->first();
            $this->templateProcessor->setValue('job' . $i, $job->description);
        }

        return $this;
    }

    public function deleteDocuments($documents)
    {
        foreach ($documents as $document) {
            unlink("files/" . $document);
        }
    }
}