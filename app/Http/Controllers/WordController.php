<?php

namespace App\Http\Controllers;


use App\Classes\DocumentFactory;
use Illuminate\Http\Request;
use App\Classes\ZipFactory;
use PhpOffice\PhpWord\TemplateProcessor;

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

    public function prepareDocument(Request $request)
    {
        $this->validate($request, [
            'start' => 'required|date|max:100',
            'end' => 'required|date|max:100',
            'year' => 'required|integer|max:3',
            'nr' => 'required|integer|max:100'
        ]);

        $documentFactory = new DocumentFactory();
        $documents = $documentFactory->createDocumentsWith($request->toArray());
        $zipFactory = new ZipFactory();
        $zipFactory->createZipFromDocuments($documents);
        $documentFactory->deleteDocuments($documents);

        return response()->download($zipFactory->getZipPatch())->deleteFileAfterSend(true);
    }
}
