<?php

namespace TobiasDierich\Gauge\Http\Controllers;

use Illuminate\Routing\Controller;
use TobiasDierich\Gauge\Contracts\EntriesRepository;
use TobiasDierich\Gauge\Storage\FamilyQueryOptions;

class RequestsController extends Controller
{
    /**
     * Display the requests overview.
     *
     * @param \TobiasDierich\Gauge\Contracts\EntriesRepository $storage
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(EntriesRepository $storage)
    {
        $options = (new FamilyQueryOptions())
            ->orderBy('duration_total')
            ->limit(5);

        return view('gauge::requests', [
            'requests' => $storage->getFamilies('request', $options),
        ]);
    }
}
