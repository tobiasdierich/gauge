<?php

namespace TobiasDierich\Gauge\Storage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
     * @param \Illuminate\Database\Eloquent\Builder           $query
     * @param string                                          $type
     * @param \TobiasDierich\Gauge\Storage\FamilyQueryOptions $options
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithFamilyOptions($query, $type, FamilyQueryOptions $options)
    {
        $this->addFamilySelects($query)
            ->whereType($query, $type)
            ->whereFamilyHash($query, $options)
            ->orderFamilyBy($query, $options);

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
     * @param \Illuminate\Database\Eloquent\Builder           $query
     * @param \TobiasDierich\Gauge\Storage\FamilyQueryOptions $options
     *
     * @return $this
     */
    protected function whereFamilyHash($query, FamilyQueryOptions $options)
    {
        $query->when($options->familyHash, function ($query, $hash) {
            return $query->where('family_hash', $hash);
        });

        return $this;
    }

    /**
     * Order the query by the given column.
     *
     * @param \Illuminate\Database\Eloquent\Builder           $query
     * @param \TobiasDierich\Gauge\Storage\FamilyQueryOptions $options
     *
     * @return $this
     */
    protected function orderFamilyBy($query, FamilyQueryOptions $options)
    {
        $query->when($options->orderBy, function ($query, $orderBy) {
            return $query->orderBy($orderBy, 'desc');
        });

        return $this;
    }

    /**
     * Add family related selects to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return $this
     */
    protected function addFamilySelects($query)
    {
        $sub = EntryModel::query()
            ->addSelect([
                DB::raw('count(*) as count'),
                DB::raw('sum(duration) as total'),
                DB::raw('(sum(duration) / count(*)) as avg'),
                DB::raw('min(sequence) as sequence2'),
                DB::raw('max(created_at) as last_seen')
            ])
            ->groupBy(['family_hash', 'type']);

        $query->addSelect([
            'type',
            'family_hash',
            'content',
            DB::raw('aggregates.count as count'),
            DB::raw('aggregates.total as duration_total'),
            DB::raw('aggregates.avg as duration_average'),
            DB::raw('aggregates.last_seen as last_seen')
        ])->joinSub($sub, 'aggregates', function ($join) {
            $join->on('sequence', '=', 'aggregates.sequence2');
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
