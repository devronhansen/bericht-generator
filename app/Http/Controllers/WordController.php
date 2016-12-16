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
            'start' => 'required|date|max:100',
            'end' => 'required|date|max:100',
            'year' => 'required|integer|max:3',
            'nr' => 'required|integer|max:100'
        ]);

        $documentFactory = new DocumentFactory();
        $documentFactory->createDocumentsWith($request->toArray())
            ->getZipFromDocuments()
            ->deleteDocuments(true);
        return response()->download($documentFactory->getZipPath())->deleteFileAfterSend(true);
    }
}
