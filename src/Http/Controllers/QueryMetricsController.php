<?php

namespace TobiasDierich\Gauge\Http\Controllers;

use TobiasDierich\Gauge\EntryType;

class QueryMetricsController extends MetricController
{
    /**
     * @return string
     */
    protected function entryType()
    {
        return EntryType::QUERY;
    }
}
