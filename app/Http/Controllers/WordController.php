<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Traits\WordTransactions;

class WordController extends Controller
{
    use WordTransactions;

    function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        return view('word/index');
    }

    public function createDocument(Request $request)
    {
        $this->setFileNameFromRequest($request)
            ->getDocumentWithRequestValues($request)
            ->saveAs($this->fileName);
        return response()->download($this->fileName)->deleteFileAfterSend(true);
    }
}
