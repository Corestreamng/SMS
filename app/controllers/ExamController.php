<?php

namespace App\Controllers;

use App\Core\Controller;

class ExamController extends Controller
{
    public function index()
    {
        $this->view('exams.index');
    }
}
