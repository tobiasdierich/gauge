<?php

namespace TobiasDierich\Gauge\Console;

use Illuminate\Console\Command;
use TobiasDierich\Gauge\Contracts\PrunableRepository;

class PruneCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gauge:prune {--hours=24 : The number of hours to retain Gauge data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune stale entries from the Gauge database';

    /**
     * Execute the console command.
     *
     * @param \TobiasDierich\Gauge\Contracts\PrunableRepository $repository
     *
     * @return void
     */
    public function handle(PrunableRepository $repository)
    {
        $this->info($repository->prune(now()->subHours($this->option('hours'))) . ' entries pruned.');
    }
}
