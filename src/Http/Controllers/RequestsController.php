<?php

namespace TobiasDierich\Gauge\Http\Controllers;

use Illuminate\Http\Request;
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

    /**
     * Display the given request family.
     *
     * @param string                                           $familyHash
     * @param \TobiasDierich\Gauge\Contracts\EntriesRepository $storage
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(string $familyHash, EntriesRepository $storage)
    {
        $options = (new FamilyQueryOptions())
            ->familyHash($familyHash)
            ->orderBy('created_at')
            ->limit(1);

        return view('gauge::request', [
            'request'  => $storage->getFamilies('request', $options)->first(),
            'requests' => $storage->getFamilyEntries('request', $familyHash),
        ]);
    }
}
