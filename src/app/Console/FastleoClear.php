<?php

namespace Fastleo\Fastleo;

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
     * @return mixed
     */
    public function handle()
    {
        @file_put_contents(base_path('storage/logs/laravel.log'), '');
        return true;
    }
}
