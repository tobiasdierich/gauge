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
     * @param \Illuminate\Database\Eloquent\Builder          $query
     * @param string                                         $type
     * @param \TobiasDierich\Gauge\Storage\FamilyQueryOptions $options
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithFamilyOptions($query, $type, FamilyQueryOptions $options)
    {
        $this->addFamilySelects($query)
            ->whereType($query, $type)
            ->whereFamilyHash($query, $options);

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
     * Add family related selects to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return $this
     */
    public function addFamilySelects($query)
    {
        $sub = EntryModel::query()
            ->addSelect([
                DB::raw('count(*) as count'),
                DB::raw('sum(duration) as total'),
                DB::raw('(sum(duration) / count(*)) as avg'),
                DB::raw('min(sequence) as sequence2'),
            ])
            ->groupBy(['family_hash', 'type']);

        $query->addSelect([
            'type',
            'family_hash',
            'content',
            DB::raw('aggregates.count as count'),
            DB::raw('aggregates.total as duration_total'),
            DB::raw('aggregates.avg as duration_average')
        ])->joinSub($sub, 'aggregates', function ($join) {
            $join->on('sequence', '=', 'aggregates.sequence2');
        });

        return $this;
    }

    /**
     * Order the query by the average family cost.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $direction
     *
     * @return $this
     */
    protected function orderByAverageFamilyCost($query, string $direction = 'desc')
    {
        $query->groupBy('family_hash')
            ->addSelect(DB::raw('(sum(duration) / count(*)) as avg'))
            ->orderBy('avg', $direction);

        return $this;
    }

    /**
     * Order the query by the total family cost.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $direction
     *
     * @return $this
     */
    protected function orderByTotalFamilyCost($query, string $direction = 'desc')
    {
        $query->groupBy('family_hash')
            ->addSelect(DB::raw('sum(duration) as total'))
            ->orderBy('avg', $direction);

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
