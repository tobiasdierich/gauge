<?php

namespace TobiasDierich\Gauge;

class GaugeHelper
{
    /**
     * @param int $nanoseconds
     *
     * @return string
     */
    public static function formatNanoseconds(int $nanoseconds)
    {
        $units = ['ns', 'ms', 's'];
        $precision = 0;

        $pow = floor(($nanoseconds ? log($nanoseconds) : 0) / log(1000));
        $pow = min($pow, count($units) - 1);

        $nanoseconds /= pow(1000, $pow);

        if ($units[$pow] === 's' && $nanoseconds < 100) {
            $precision = 2;
        }

        return number_format(round($nanoseconds, $precision), $precision) . $units[$pow];
    }
}
