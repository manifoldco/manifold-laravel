<?php

namespace manifoldco\manifold\Commands;

use Illuminate\Console\Command;
use manifoldco\manifold\Core;

class Refresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manifold:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete cached Manifold configs and pull fresh data from your account.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // $n = $this->argument('n');
        $core = new Core;
        $core->refresh();
        echo "cached configs cleared, fresh data pulled from your account.\n";
    }
}
