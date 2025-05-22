<?php

namespace App\Http\Controllers\CMS;

use App\Services\CMS\HistoryService;

class HistoryController extends CMSController
{
    private array $rootBreadcrumbs = ['History' => null];

    public function __construct(
        public HistoryService $historyService,
    ) {
        $this->rootBreadcrumbs['History'] = route('admin.histories.index');
    }

    public function index()
    {
        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'List' => null
        ]);

        $tests = $this->historyService->getPaginateHistoryTests();

        return view('histories.index', compact('tests'));
    }

    public function getDetailHistoryTest($testId)
    {
        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'List' => null
        ]);

        $test = $this->historyService->getDetailHistoryTest($testId);

        return view('histories.test-detail', compact('test'));
    }
}