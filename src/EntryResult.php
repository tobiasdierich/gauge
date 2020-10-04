<?php

namespace TobiasDierich\Gauge;

use Carbon\Carbon;
use JsonSerializable;

class EntryResult implements JsonSerializable
{
    /**
     * The entry's type.
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
     * The entry's content.
     *
     * @var array
     */
    public $content = [];

    /**
     * The total duration of this entry.
     *
     * @var int
     */
    public $duration;

    /**
     * The date & time of the entry.
     *
     * @var \Carbon\Carbon
     */
    public $created_at;

    /**
     * Create a new entry result instance.
     *
     * @param string         $type
     * @param string         $familyHash
     * @param array          $content
     * @param int            $duration
     * @param \Carbon\Carbon $created_at
     */
    public function __construct(string $type, string $familyHash, array $content, int $duration, Carbon $created_at)
    {
        $this->type = $type;
        $this->content = $content;
        $this->familyHash = $familyHash;
        $this->duration = $duration;
        $this->created_at = $created_at;
    }

    /**
     * Get the array representation of the entry.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'type'        => $this->type,
            'content'     => $this->content,
            'family_hash' => $this->familyHash,
            'duration'    => $this->duration,
            'created_at'  => $this->created_at,
        ];
    }
}
