<?php

namespace TobiasDierich\Gauge;

use Illuminate\Support\Str;

class IncomingEntry
{
    /**
     * The entry's UUID.
     *
     * @var string
     */
    public $uuid;

    /**
     * The entry's type.
     *
     * @var string
     */
    public $type;

    /**
     * The entry's family hash.
     *
     * @var string|null
     */
    public $familyHash;

    /**
     * The entry's execution duration.
     *
     * @var int
     */
    public $duration;

    /**
     * The currently authenticated user, if applicable.
     *
     * @var mixed
     */
    public $user;

    /**
     * The entry's content.
     *
     * @var array
     */
    public $content = [];

    /**
     * The DateTime that indicates when the entry was recorded.
     *
     * @var \DateTimeInterface
     */
    public $recordedAt;

    /**
     * Create a new incoming entry instance.
     *
     * @param array $content
     *
     * @return void
     */
    public function __construct(array $content)
    {
        $this->uuid = (string) Str::orderedUuid();

        $this->recordedAt = now();

        $this->content = array_merge($content, ['hostname' => gethostname()]);
    }

    /**
     * Create a new entry instance.
     *
     * @param mixed ...$arguments
     *
     * @return static
     */
    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }

    /**
     * Assign the entry a given type.
     *
     * @param string $type
     *
     * @return $this
     */
    public function type(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param int $duration
     *
     * @return $this
     */
    public function duration(int $duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Assign the entry a family hash.
     *
     * @param string $familyHash
     *
     * @return $this
     */
    public function withFamilyHash(string $familyHash)
    {
        $this->familyHash = $familyHash;

        return $this;
    }

    /**
     * Set the currently authenticated user.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     *
     * @return $this
     */
    public function user($user)
    {
        $this->user = $user;

        $this->content = array_merge($this->content, [
            'user' => [
                'id'    => $user->getAuthIdentifier(),
                'name'  => $user->name ?? null,
                'email' => $user->email ?? null,
            ],
        ]);

        return $this;
    }

    /**
     * Determine if the incoming entry is a failed request.
     *
     * @return bool
     */
    public function isFailedRequest()
    {
        return $this->type === EntryType::REQUEST &&
            ($this->content['response_status'] ?? 200) >= 500;
    }

    /**
     * Determine if the incoming entry is a query.
     *
     * @return bool
     */
    public function isQuery()
    {
        return $this->type === EntryType::QUERY;
    }

    /**
     * Get the family look-up hash for the incoming entry.
     *
     * @return string|null
     */
    public function familyHash()
    {
        return $this->familyHash;
    }

    /**
     * Get an array representation of the entry for storage.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'uuid'        => $this->uuid,
            'family_hash' => $this->familyHash,
            'type'        => $this->type,
            'duration'    => $this->duration,
            'content'     => $this->content,
            'created_at'  => $this->recordedAt->toDateTimeString(),
        ];
    }
}
