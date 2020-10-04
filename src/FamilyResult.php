<?php

namespace TobiasDierich\Gauge;

use Carbon\Carbon;
use JsonSerializable;

class FamilyResult implements JsonSerializable
{
    /**
     * The family's type.
     *
     * @var string
     */
    public $type;

    /**
     * The family hash.
     *
     * @var string|null
     */
    public $familyHash;

    /**
     * The family's content.
     *
     * @var array
     */
    public $content = [];

    /**
     * The number of entries in the family.
     *
     * @var int
     */
    public $count;

    /**
     * The total duration of all family entries.
     *
     * @var int
     */
    public $duration_total;

    /**
     * The average duration of all family entries.
     *
     * @var int
     */
    public $duration_average;

    /**
     * The date & time when the request family was last seen.
     *
     * @var \Carbon\Carbon
     */
    public $last_seen;

    /**
     * Create a new family result instance.
     *
     * @param string         $type
     * @param string         $familyHash
     * @param array          $content
     * @param int            $count
     * @param int            $duration_total
     * @param int            $duration_average
     * @param \Carbon\Carbon $last_seen
     */
    public function __construct(string $type, string $familyHash, array $content, int $count, int $duration_total, int $duration_average, Carbon $last_seen)
    {
        $this->type = $type;
        $this->content = $content;
        $this->familyHash = $familyHash;
        $this->count = $count;
        $this->duration_total = $duration_total;
        $this->duration_average = $duration_average;
        $this->last_seen = $last_seen;
    }

    /**
     * Get the array representation of the entry.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'type'             => $this->type,
            'content'          => $this->content,
            'family_hash'      => $this->familyHash,
            'count'            => $this->count,
            'duration_total'   => $this->duration_total,
            'duration_average' => $this->duration_average,
            'last_seen'        => $this->last_seen,
        ];
    }
}
