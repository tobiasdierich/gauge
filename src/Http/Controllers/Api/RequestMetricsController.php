<?php

namespace TobiasDierich\Gauge\Http\Controllers\Api;

use TobiasDierich\Gauge\EntryType;

class RequestMetricsController extends MetricController
{
    /**
     * @return string
     */
    protected function entryType()
    {
        return EntryType::REQUEST;
    }
}
