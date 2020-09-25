<?php

namespace TobiasDierich\Gauge\Storage;

use DateTimeInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use TobiasDierich\Gauge\Contracts\ClearableRepository;
use TobiasDierich\Gauge\Contracts\EntriesRepository as Contract;
use TobiasDierich\Gauge\Contracts\PrunableRepository;
use TobiasDierich\Gauge\EntryResult;

class DatabaseEntriesRepository implements Contract, ClearableRepository, PrunableRepository
{
    /**
     * The database connection name that should be used.
     *
     * @var string
     */
    protected $connection;

    /**
     * The number of entries that will be inserted at once into the database.
     *
     * @var int
     */
    protected $chunkSize = 1000;

    /**
     * Create a new database repository.
     *
     * @param string $connection
     * @param int    $chunkSize
     *
     * @return void
     */
    public function __construct(string $connection, int $chunkSize = null)
    {
        $this->connection = $connection;

        if ($chunkSize) {
            $this->chunkSize = $chunkSize;
        }
    }

    /**
     * Find the entry with the given ID.
     *
     * @param mixed $id
     *
     * @return \TobiasDierich\Gauge\EntryResult
     */
    public function find($id): EntryResult
    {
        $entry = EntryModel::on($this->connection)->whereUuid($id)->firstOrFail();

        return new EntryResult(
            $entry->uuid,
            null,
            $entry->batch_id,
            $entry->type,
            $entry->family_hash,
            $entry->content,
            $entry->created_at
        );
    }

    /**
     * Return all the entries of a given type.
     *
     * @param string|null                                    $type
     * @param \TobiasDierich\Gauge\Storage\EntryQueryOptions $options
     *
     * @return \Illuminate\Support\Collection|\TobiasDierich\Gauge\EntryResult[]
     */
    public function get($type, EntryQueryOptions $options)
    {
        return EntryModel::on($this->connection)
            ->withGaugeOptions($type, $options)
            ->take($options->limit)
            ->orderByDesc('sequence')
            ->get()->reject(function ($entry) {
                return !is_array($entry->content);
            })->map(function ($entry) {
                return new EntryResult(
                    $entry->uuid,
                    $entry->sequence,
                    $entry->batch_id,
                    $entry->type,
                    $entry->family_hash,
                    $entry->content,
                    $entry->created_at
                );
            })->values();
    }

    /**
     * Store the given array of entries.
     *
     * @param \Illuminate\Support\Collection|\TobiasDierich\Gauge\IncomingEntry[] $entries
     *
     * @return void
     */
    public function store(Collection $entries)
    {
        if ($entries->isEmpty()) {
            return;
        }

        $table = $this->table('gauge_entries');

        $entries->chunk($this->chunkSize)->each(function ($chunked) use ($table) {
            $table->insert($chunked->map(function ($entry) {
                $entry->content = json_encode($entry->content);

                return $entry->toArray();
            })->toArray());
        });
    }

    /**
     * Prune all of the entries older than the given date.
     *
     * @param \DateTimeInterface $before
     *
     * @return int
     */
    public function prune(DateTimeInterface $before)
    {
        $query = $this->table('gauge_entries')
            ->where('created_at', '<', $before);

        $totalDeleted = 0;

        do {
            $deleted = $query->take($this->chunkSize)->delete();

            $totalDeleted += $deleted;
        } while ($deleted !== 0);

        return $totalDeleted;
    }

    /**
     * Clear all the entries.
     *
     * @return void
     */
    public function clear()
    {
        $this->table('gauge_entries')->delete();
    }

    /**
     * Get a query builder instance for the given table.
     *
     * @param string $table
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function table($table)
    {
        return DB::connection($this->connection)->table($table);
    }
}
