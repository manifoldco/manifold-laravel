<?php

namespace manifoldco\manifold\Commands;

use Illuminate\Console\Command;
use manifoldco\manifold\Core;

class Check extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manifold:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if your provided Manifold token is valid.';

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
        if($core->test()){
            echo "Looks like your token is valid.\n";
        }else{
            echo "Looks like your token is invalid.\n";
        }

    }
}
