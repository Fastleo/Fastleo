<?php

namespace Fastleo\Fastleo\app\Console;

use Illuminate\Console\Command;

class FastleoResource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fastleo:resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update resources';

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
     * @return bool
     */
    public function handle()
    {
        \Artisan::call('vendor:publish --tag=fastleo');
        return true;
    }
}
