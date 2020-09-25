<?php

namespace TobiasDierich\Gauge\Storage;

use Illuminate\Database\Eloquent\Model;

class EntryModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gauge_entries';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = null;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'content' => 'json',
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Prevent Eloquent from overriding uuid with `lastInsertId`.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Scope the query for the given query options.
     *
     * @param \Illuminate\Database\Eloquent\Builder          $query
     * @param string                                         $type
     * @param \TobiasDierich\Gauge\Storage\EntryQueryOptions $options
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithGaugeOptions($query, $type, EntryQueryOptions $options)
    {
        $this->whereType($query, $type)
            ->whereFamilyHash($query, $options)
            ->whereBeforeSequence($query, $options);

        return $query;
    }

    /**
     * Scope the query for the given type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $type
     *
     * @return $this
     */
    protected function whereType($query, $type)
    {
        $query->when($type, function ($query, $type) {
            return $query->where('type', $type);
        });

        return $this;
    }

    /**
     * Scope the query for the given type.
     *
     * @param \Illuminate\Database\Eloquent\Builder          $query
     * @param \TobiasDierich\Gauge\Storage\EntryQueryOptions $options
     *
     * @return $this
     */
    protected function whereFamilyHash($query, EntryQueryOptions $options)
    {
        $query->when($options->familyHash, function ($query, $hash) {
            return $query->where('family_hash', $hash);
        });

        return $this;
    }

    /**
     * Scope the query for the given pagination options.
     *
     * @param \Illuminate\Database\Eloquent\Builder          $query
     * @param \TobiasDierich\Gauge\Storage\EntryQueryOptions $options
     *
     * @return $this
     */
    protected function whereBeforeSequence($query, EntryQueryOptions $options)
    {
        $query->when($options->beforeSequence, function ($query, $beforeSequence) {
            return $query->where('sequence', '<', $beforeSequence);
        });

        return $this;
    }

    /**
     * Get the current connection name for the model.
     *
     * @return string
     */
    public function getConnectionName()
    {
        return config('gauge.storage.database.connection');
    }
}
