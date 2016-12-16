<?php

namespace App\Http\Controllers;


use App\Classes\DocumentFactory;
use Illuminate\Http\Request;

class WordController extends Controller
{
    function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        return view('word/index');
    }

    public function prepareDocument(Request $request)
    {
        $this->validate($request, [
            'start' => 'required',
            'end' => 'required',
            'year' => 'required',
            'nr' => 'required'
        ]);

        $documentFactory = new DocumentFactory();
        $documentFactory->createDocumentsWith($request->toArray())
            ->getZipFromDocuments()
            ->deleteDocuments(true);
        return response()->download($documentFactory->getZipPath())->deleteFileAfterSend(true);
    }
}
