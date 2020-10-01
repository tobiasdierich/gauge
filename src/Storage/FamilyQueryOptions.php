<?php

namespace TobiasDierich\Gauge\Storage;

use Illuminate\Http\Request;

class FamilyQueryOptions
{
    /**
     * The family hash that must belong to retrieved entries.
     *
     * @var string
     */
    public $familyHash;

    /**
     * The number of entries to retrieve.
     *
     * @var int
     */
    public $limit = 50;

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
            ->familyHash($request->family_hash)
            ->limit($request->take ?? 50);
    }

    /**
     * Set the family hash that must belong to retrieved entries.
     *
     * @param string $familyHash
     *
     * @return $this
     */
    public function familyHash(?string $familyHash)
    {
        $this->familyHash = $familyHash;

        return $this;
    }

    /**
     * Set the number of entries that should be retrieved.
     *
     * @param int $limit
     *
     * @return $this
     */
    public function limit(int $limit)
    {
        $this->limit = $limit;

        return $this;
    }
}
