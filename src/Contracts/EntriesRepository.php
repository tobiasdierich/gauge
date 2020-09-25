<?php

namespace TobiasDierich\Gauge\Contracts;

use Illuminate\Support\Collection;
use TobiasDierich\Gauge\EntryResult;
use TobiasDierich\Gauge\Storage\EntryQueryOptions;

interface EntriesRepository
{
    /**
     * Return an entry with the given ID.
     *
     * @param mixed $id
     *
     * @return \TobiasDierich\Gauge\EntryResult
     */
    public function find($id): EntryResult;

    /**
     * Return all the entries of a given type.
     *
     * @param string|null                                    $type
     * @param \TobiasDierich\Gauge\Storage\EntryQueryOptions $options
     *
     * @return \Illuminate\Support\Collection|\TobiasDierich\Gauge\EntryResult[]
     */
    public function get($type, EntryQueryOptions $options);

    /**
     * Store the given entries.
     *
     * @param \Illuminate\Support\Collection|\TobiasDierich\Gauge\IncomingEntry[] $entries
     *
     * @return void
     */
    public function store(Collection $entries);
}
