<?php

namespace App\Http\Controllers\CMS;

use App\Services\CMS\SkillService;
use App\Services\CMS\TestService;

class TestController extends CMSController
{
    private array $rootBreadcrumbs = ['Test' => null];

    public function __construct(
        public TestService $testService,
    ) {
        $this->rootBreadcrumbs['Test'] = route('admin.tests.index');
    }

    public function index()
    {
        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'List' => null
        ]);

        $tests = $this->testService->getPaginateTests();

        return view('tests.index', compact('tests'));
    }

    public function create()
    {
        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'Create' => null
        ]);

        return view('tests.create');
    }
}