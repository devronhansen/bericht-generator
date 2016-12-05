<?php
namespace App\Traits;

use App\anwendungsentwickler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\TemplateProcessor;

trait WordTransactions
{
    protected $templateProcessor;
    protected $fileName;

    protected function CreateTemplate()
    {
        $this->templateProcessor = new TemplateProcessor('template.docx');
    }

    public function setRequestValuesInDocument(Request $request)
    {
        $this->CreateTemplate();
        $this->templateProcessor->setValue('jahr', $request->input('year'));
        $this->templateProcessor->setValue('nr', $request->input('nr'));
        $this->templateProcessor->setValue('name', Auth::user()->name);
        $this->templateProcessor->setValue('start', $request->input('start'));
        $this->templateProcessor->setValue('end', $request->input('end'));

        for ($i = 1; $i < 16; $i++) {
            $job = anwendungsentwickler::where('id', mt_rand(1, 10))->first();
            $this->templateProcessor->setValue('job' . $i, $job->description);
        }

        return $this->templateProcessor;
    }

    public function setFileNameFromRequest(Request $request)
    {
        $this->fileName="BerichtNr" . $request->input('nr') . ".docx";
    }
}