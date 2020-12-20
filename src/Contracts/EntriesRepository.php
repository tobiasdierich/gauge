<?php

namespace TobiasDierich\Gauge\Contracts;

use Illuminate\Support\Collection;
use TobiasDierich\Gauge\Storage\FamilyQueryOptions;

interface EntriesRepository
{
    /**
     * Return one family of a given type.
     *
     * @param string $type
     * @param string $options
     *
     * @return \TobiasDierich\Gauge\FamilyResult|null
     */
    public function getFamily($type, $familyHash);

    /**
     * Return all the entry families of a given type.
     *
     * @param string|null                                     $type
     * @param \TobiasDierich\Gauge\Storage\FamilyQueryOptions $options
     *
     * @return \Illuminate\Support\Collection|\TobiasDierich\Gauge\FamilyResult[]
     */
    public function getFamilies($type, FamilyQueryOptions $options);

    /**
     * Return all the entries of a family of a given type.
     *
     * @param string $type
     * @param string $familyHash
     *
     * @return \Illuminate\Support\Collection|\TobiasDierich\Gauge\FamilyResult[]
     */
    public function getFamilyEntries($type, $familyHash);

    /**
     * Store the given entries.
     *
     * @param \Illuminate\Support\Collection|\TobiasDierich\Gauge\IncomingEntry[] $entries
     *
     * @return void
     */
    public function store(Collection $entries);
}
