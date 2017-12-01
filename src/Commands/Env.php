<?php

namespace manifoldco\manifold\Commands;

use Illuminate\Console\Command;
use manifoldco\manifold\Core;

class Env extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manifold:env';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copies your configurations from Manifold into your .env file.';

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
        $begin_phrase = "### BEGIN MANIFOLD VARIABLES ###\n";
        $end_phrase = "### END MANIFOLD VARIABLES ###\n";

        $env_file = app()->environmentFilePath();
        $lines = file($env_file);

        $core = new Core;
        $configs = $core->load_data();
        $new_lines = [$begin_phrase];
        foreach($configs as $key => $value){
            $new_lines[] = "$key=\"$value\"\n";
        }
        $new_lines[] = $end_phrase;

        $output_lines = [];

        $begin_index = array_search($begin_phrase, $lines);
        if($begin_index === false){
            $output_lines = array_merge($lines, $new_lines);
        }else{
            $end_index = array_search($end_phrase, $lines);
            if($end_index === false){
                $output_lines = array_merge(
                    array_splice($lines, 0, $begin_index),
                    $new_lines
                );
            }else{
                $first_lines = $lines;
                $last_lines = $lines;
                $output_lines = array_merge(
                    array_splice($first_lines, 0, $begin_index),
                    $new_lines,
                    array_splice($last_lines, $end_index+1)
                );
            }
        }
        $output = implode($output_lines);
        file_put_contents($env_file, $output);

        echo ".env file updated.\n";

    }
}
