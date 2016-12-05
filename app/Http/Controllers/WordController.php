<?php

namespace App\Http\Controllers;

use App\anwendungsentwickler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class WordController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('word/index');
    }

    public function createDocument(Request $request)
    {
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('template.docx');
        $templateProcessor->setValue('jahr', $request->input('year'));
        $templateProcessor->setValue('nr', $request->input('nr'));
        $templateProcessor->setValue('name', Auth::user()->name);
        $templateProcessor->setValue('start', $request->input('start'));
        $templateProcessor->setValue('end', $request->input('end'));

        for ($i = 1; $i < 16; $i++) {
            $job = anwendungsentwickler::where('id', mt_rand(1, 10))->first();
            $templateProcessor->setValue('job' . $i, $job->description);
        }

        $templateProcessor->saveAs('result.docx');
        return response()->download('result.docx')->deleteFileAfterSend(true);
    }
}
