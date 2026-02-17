<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ElectionController extends Controller
{
    public function index():View{
        return view('electioncontrol');
    }

    public function indexPosistionSetup():View{
        return view('posistionsetup');
    }

    public function indexCandidateList():View{
        return view('candidatelist');
    }
}

