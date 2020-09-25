<?php

namespace TobiasDierich\Gauge\Storage;

use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class MetricsRepository
{
    /**
     * The database connection name that should be used.
     *
     * @var string
     */
    protected $connection;

    /**
     * Create a new metrics repository.
     *
     * @param string $connection
     *
     * @return void
     */
    public function __construct(string $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Query the given metric.
     *
     * @param string                                          $type
     * @param string                                          $metric
     * @param \TobiasDierich\Gauge\Storage\MetricQueryOptions $options
     *
     * @return array
     */
    public function queryMetric(string $type, string $metric, MetricQueryOptions $options)
    {
        if (!$this->hasMetric($metric)) {
            throw new \RuntimeException("Metric '{$metric}' does not exist.");
        }

        $method = 'get' . ucfirst(Str::camel($metric)) . 'Metric';

        return $this->{$method}($type, $options);
    }

    /**
     * Get the throughput for the given type, e.g. requests per second.
     *
     * @param string                                          $type
     * @param \TobiasDierich\Gauge\Storage\MetricQueryOptions $options
     *
     * @return array
     */
    protected function getThroughputMetric(string $type, MetricQueryOptions $options)
    {
        $ranges = $this->generateDateRanges($options->startDate, $options->unit, $options->unitStep);

        return $ranges->map(function ($range) use ($type) {
            $range->value = EntryModel::on($this->connection)
                ->whereType($type)
                ->whereBetween('created_at', [$range->startDate, $range->endDate])
                ->count();

            return $range;
        })
            ->map(function ($metric) {
                $metric->value = round($metric->value / $metric->startDate->diffInSeconds($metric->endDate), 2);

                return $metric;
            })->all();
    }

    /**
     * Generate the date ranges from $startDate until now with the given unit and unit steps.
     *
     * @param \Illuminate\Support\Carbon $startDate
     * @param string                     $unit
     * @param int                        $unitStep
     *
     * @return \Illuminate\Support\Collection
     */
    protected function generateDateRanges(Carbon $startDate, string $unit, int $unitStep)
    {
        if (method_exists($startDate, 'startOf' . ucfirst($unit))) {
            $startDate->{'startOf' . ucfirst($unit)}();
        }

        return collect(CarbonPeriod::create($startDate, "{$unitStep} {$unit}s", now()))
            ->map(function ($carbonRange) use ($unit, $unitStep) {
                $range = new \stdClass();
                $range->startDate = $carbonRange;
                $range->endDate = $carbonRange->copy()
                    ->{'add' . Str::plural(ucfirst($unit))}($unitStep)
                    ->subSecond()
                    ->{'endOf' . $unit}();

                return $range;
            });
    }

    /**
     * Check if this repository provides a specific metric.
     *
     * @param string $metric
     *
     * @return bool
     */
    protected function hasMetric(string $metric)
    {
        return collect($this->getProvidedMetrics())->contains(function ($challenge) use ($metric) {
            return Str::contains($challenge, ucfirst(Str::camel($metric)));
        });
    }

    /**
     * Get the metrics provided by this repository.
     *
     * @return array
     */
    protected function getProvidedMetrics()
    {
        return collect(get_class_methods($this))->filter(function ($method) {
            return Str::startsWith($method, 'get') && Str::endsWith($method, 'Metric');
        })->all();
    }
}
