<?php
namespace App\Traits;

use App\anwendungsentwickler;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\Shared\ZipArchive;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;

trait WordTransactions
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


    public function getZipWithDocuments(Request $request)
    {
        $this->openZipArchive()
        ->parseRequestInputs($request)
        ->createDocumentsAndZip()
        ->deleteLeftDocuments();
    }

    protected function openZipArchive()
    {
        $this->zip = new ZipArchive();
        $this->zip->open('result.zip', ZipArchive::CREATE);

        return $this;
    }

    protected function parseRequestInputs($request)
    {
        $this->startWeek = Carbon::parse($request->input('start'))->weekOfYear;
        $this->endWeek = Carbon::parse($request->input('end'))->weekOfYear;
        $this->date = Carbon::createFromFormat('d.m.Y', $request->input('start'))->startOfWeek();
        $this->nr = $request->input('nr');
        $this->year = $request->input('year');

        return $this;
    }

    protected function createDocumentsAndZip()
    {
        for ($currentWeek = $this->startWeek; $currentWeek <= $this->endWeek; $currentWeek++) {
            $this->createTemplate();
            $this->setValuesInDocument();
            $this->date = $this->date->addDay(3);
            $this->nr++;
            $this->templateProcessor->saveAs("/files" . $this->fileName);
            $this->zip->addFile(basename($this->fileName), $this->fileName);
            array_push($this->fileNames, $this->fileName);
        }

        $this->zip->close();

        return $this;
    }

    protected function createTemplate()
    {
        $this->templateProcessor = new TemplateProcessor('template.docx');
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
        $this->setFileNameFromRequest();

        return $this;
    }

    protected function setFileNameFromRequest()
    {
        $this->fileName = "BerichtNr" . $this->nr . ".docx";

        return $this;
    }

    protected function setJobValues()
    {
        for ($i = 1; $i < 16; $i++) {
            $job = anwendungsentwickler::where('id', mt_rand(1, 10))->first();
            $this->templateProcessor->setValue('job' . $i, $job->description);
        }

        return $this;
    }

    protected function deleteLeftDocuments()
    {
        foreach ($this->fileNames as $filename) {
            unlink($filename);
        }

        return $this;
    }
}