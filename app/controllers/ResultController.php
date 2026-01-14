<?php

namespace App\Controllers;

use App\Core\Controller;

class ResultController extends Controller
{
    public function index()
    {
        $this->view('results.index');
    }
}
