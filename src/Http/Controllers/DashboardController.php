<?php

namespace TobiasDierich\Gauge\Http\Controllers;

use Illuminate\Routing\Controller;
use TobiasDierich\Gauge\Contracts\EntriesRepository;
use TobiasDierich\Gauge\Storage\FamilyQueryOptions;

class DashboardController extends Controller
{
    /**
     * Display the Gauge dashboard.
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

        return view('gauge::dashboard', [
            'requests' => $storage->getFamilies('request', $options),
            'queries'  => $storage->getFamilies('query', $options),
        ]);
    }
}
