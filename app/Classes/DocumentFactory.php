<?php

namespace App\Classes;

use Carbon\Carbon;
use League\Flysystem\FileNotFoundException;
use PhpOffice\PhpWord\TemplateProcessor;
use App\anwendungsentwickler;

class DocumentFactory
{
    /**
     * @var TemplateProcessor
     */
    protected $templateProcessor;
    /**
     * @var array
     */
    protected $fileNames = [];
    /**
     * @var Carbon
     */
    protected $startWeek;
    /**
     * @var  Carbon
     */
    protected $endWeek;
    /**
     * @var Carbon
     */
    protected $date;
    protected $year;
    protected $nr;
    const TEMPLATE_PATH = '/template.docx';

    /*function __construct(TemplateProcessor $templateProcessor)
    {
        $this->templateProcessor = $templateProcessor;
    }*/

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
        if (!file_exists(app_path() . self::TEMPLATE_PATH)) {
            throw new FileNotFoundException(self::TEMPLATE_PATH);
        }
        $this->setValuesInTemplateDocument();
    }

    protected function setValuesInTemplateDocument()
    {
        for ($currentWeek = $this->startWeek; $currentWeek <= $this->endWeek; $currentWeek++) {
            $this->templateProcessor = new TemplateProcessor(app_path() . '/template.docx');
            $fileName = $this->setValuesInDocument();
            $this->date = $this->date->addDay(3);
            $this->nr++;
            $this->templateProcessor->saveAs(public_path() . "/files/" . $fileName);
            array_push($this->fileNames, $fileName);
        }
    }

    protected function setValuesInDocument()
    {
        $this->templateProcessor->setValue('jahr', $this->year);
        $this->templateProcessor->setValue('nr', $this->nr);
        $this->templateProcessor->setValue('name', \Auth::user()->name);
        $this->templateProcessor->setValue('start', $this->date->format('d.m.Y'));
        $date = $this->date->addDay(4);
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