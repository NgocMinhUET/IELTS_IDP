<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Services\ExamService;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function __construct(
        public ExamService $examService,
    ) {
    }

    public function index()
    {
        return view('exams.index');
    }

    public function create()
    {
        return view('exams.create');
    }

    public function store(Request $request)
    {
        return view('exams.create');
    }

    public function detail($id)
    {

    }
}