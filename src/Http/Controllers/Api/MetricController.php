<?php

namespace TobiasDierich\Gauge\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use TobiasDierich\Gauge\Storage\MetricQueryOptions;
use TobiasDierich\Gauge\Storage\MetricsRepository;

abstract class MetricController extends Controller
{
    /**
     * The entry type for the controller.
     *
     * @return string
     */
    abstract protected function entryType();

    /**
     * Get an entry with the given ID.
     *
     * @param \Illuminate\Http\Request                       $request
     * @param \TobiasDierich\Gauge\Storage\MetricsRepository $storage
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, MetricsRepository $storage)
    {
        return response()->json([
            'data' => $storage->queryMetric(
                $this->entryType(),
                $request->metric,
                MetricQueryOptions::fromRequest($request)
            )
        ]);
    }
}
