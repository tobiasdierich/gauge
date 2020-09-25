<?php

namespace TobiasDierich\Gauge\Console;

use Illuminate\Console\Command;
use TobiasDierich\Gauge\Contracts\ClearableRepository;

class ClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gauge:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all entries from Gauge';

    /**
     * Execute the console command.
     *
     * @param \TobiasDierich\Gauge\Contracts\ClearableRepository $storage
     *
     * @return void
     */
    public function handle(ClearableRepository $storage)
    {
        $storage->clear();

        $this->info('Gauge entries cleared!');
    }
}
