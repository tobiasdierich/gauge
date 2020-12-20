<?php

namespace TobiasDierich\Gauge\Storage;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use TobiasDierich\Gauge\Contracts\ClearableRepository;
use TobiasDierich\Gauge\Contracts\EntriesRepository as Contract;
use TobiasDierich\Gauge\Contracts\PrunableRepository;
use TobiasDierich\Gauge\EntryResult;
use TobiasDierich\Gauge\FamilyResult;

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
     * Return one family of a given type.
     *
     * @param string $type
     * @param string $options
     *
     * @return \TobiasDierich\Gauge\FamilyResult|null
     */
    public function getFamily($type, $familyHash)
    {
        $family = EntryModel::on($this->connection)
            ->withFamilyOptions($type, (new FamilyQueryOptions())->familyHash($familyHash))
            ->first();

        if (!$family) {
            return null;
        }

        return new FamilyResult(
            $family->type,
            $family->family_hash,
            $family->content,
            $family->count,
            $family->duration_total,
            $family->duration_average,
            Carbon::parse($family->last_seen)
        );
    }

    /**
     * Return all families of a given type.
     *
     * @param string|null                                     $type
     * @param \TobiasDierich\Gauge\Storage\FamilyQueryOptions $options
     *
     * @return \Illuminate\Support\Collection|\TobiasDierich\Gauge\FamilyResult[]
     */
    public function getFamilies($type, FamilyQueryOptions $options)
    {
        $paginatedResults = EntryModel::on($this->connection)
            ->withFamilyOptions($type, $options)
            ->paginate($options->limit);

        $paginatedResults->setCollection($paginatedResults->getCollection()->map(function ($family) {
            return new FamilyResult(
                $family->type,
                $family->family_hash,
                $family->content,
                $family->count,
                $family->duration_total,
                $family->duration_average,
                Carbon::parse($family->last_seen)
            );
        }));

        return $paginatedResults;
    }

    /**
     * Return all entries of a given type.
     *
     * @param string $type
     * @param string $familyHash
     *
     * @return \Illuminate\Support\Collection|\TobiasDierich\Gauge\FamilyResult[]
     */
    public function getFamilyEntries($type, $familyHash)
    {
        $paginatedResults = EntryModel::on($this->connection)
            ->whereFamilyHash($familyHash)
            ->whereType($type)
            ->orderByDesc('sequence')
            ->paginate(5);

        $paginatedResults->setCollection($paginatedResults->getCollection()->map(function ($entry) {
            return new EntryResult(
                $entry->type,
                $entry->family_hash,
                $entry->content,
                $entry->duration,
                $entry->created_at
            );
        }));

        return $paginatedResults;
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
    protected function table(string $table)
    {
        return DB::connection($this->connection)->table($table);
    }
}
