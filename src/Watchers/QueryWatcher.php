<?php

namespace TobiasDierich\Gauge\Watchers;

use Illuminate\Database\Events\QueryExecuted;
use TobiasDierich\Gauge\Gauge;
use TobiasDierich\Gauge\IncomingEntry;

class QueryWatcher extends Watcher
{
    use FetchesStackTrace;

    /**
     * Register the watcher.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    public function register($app)
    {
        $app['events']->listen(QueryExecuted::class, [$this, 'recordQuery']);
    }

    /**
     * Record a query was executed.
     *
     * @param \Illuminate\Database\Events\QueryExecuted $event
     *
     * @return void
     */
    public function recordQuery(QueryExecuted $event)
    {
        if (!Gauge::isRecording()) {
            return;
        }

        $time = floor($event->time * 1000);

        if ($caller = $this->getCallerFromStackTrace()) {
            $incomingEntry = IncomingEntry::make([
                'connection' => $event->connectionName,
                'bindings'   => [],
                'sql'        => $this->replaceBindings($event),
                'slow'       => isset($this->options['slow']) && $time >= $this->options['slow'],
                'file'       => $caller['file'],
                'line'       => $caller['line'],
            ])
                ->duration($time)
                ->withFamilyHash($this->familyHash($event));

            Gauge::recordQuery($incomingEntry);
        }
    }

    /**
     * Calculate the family look-up hash for the query event.
     *
     * @param \Illuminate\Database\Events\QueryExecuted $event
     *
     * @return string
     */
    public function familyHash($event)
    {
        return md5($event->sql);
    }

    /**
     * Format the given bindings to strings.
     *
     * @param \Illuminate\Database\Events\QueryExecuted $event
     *
     * @return array
     */
    protected function formatBindings($event)
    {
        return $event->connection->prepareBindings($event->bindings);
    }

    /**
     * Replace the placeholders with the actual bindings.
     *
     * @param \Illuminate\Database\Events\QueryExecuted $event
     *
     * @return string
     */
    public function replaceBindings($event)
    {
        $sql = $event->sql;

        foreach ($this->formatBindings($event) as $key => $binding) {
            $regex = is_numeric($key)
                ? "/\?(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/"
                : "/:{$key}(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/";

            if ($binding === null) {
                $binding = 'null';
            } elseif (!is_int($binding) && !is_float($binding)) {
                $binding = $event->connection->getPdo()->quote($binding);
            }

            $sql = preg_replace($regex, $binding, $sql, 1);
        }

        return $sql;
    }
}
