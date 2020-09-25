<?php

namespace TobiasDierich\Gauge\Storage;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MetricQueryOptions
{
    /**
     * @var string
     */
    public $unit;

    /**
     * @var int
     */
    public $unitStep = 1;

    /**
     * @var \Illuminate\Support\Carbon
     */
    public $startDate;

    /**
     * Create new entry query options from the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return static
     */
    public static function fromRequest(Request $request)
    {
        return (new static)
            ->unit($request->options['unit'])
            ->unitStep($request->options['unit_step'])
            ->startDate($request->options['start_date'] ? new Carbon($request->options['start_date']) : null);
    }

    /**
     * @param string $unit
     *
     * @return $this
     */
    public function unit(string $unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @param int $unitStep
     *
     * @return $this
     */
    public function unitStep(int $unitStep)
    {
        $this->unitStep = $unitStep ?? 1;

        return $this;
    }

    /**
     * @param \Illuminate\Support\Carbon|null $startDate
     *
     * @return $this
     */
    public function startDate(Carbon $startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }
}
