<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function __construct(
    ) {
    }

    public function store(Request $request)
    {
        return view('exams.create');
    }

    public function detail($id)
    {

    }
}