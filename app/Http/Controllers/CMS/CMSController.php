<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CMSController extends Controller
{
    public array $breadcrumbs = [];

    public function __construct()
    {
        Auth::shouldUse('admin');
    }
}