<?php

namespace Fastleo\Fastleo\app\Console;

use Illuminate\Console\Command;

class FastleoClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fastleo:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear laravel cache';

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
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        return true;
    }
}
