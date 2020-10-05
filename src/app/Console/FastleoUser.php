<?php

namespace Fastleo\Fastleo\app\Console;

use Illuminate\Support\Facades\Hash;
use Illuminate\Console\Command;

class FastleoUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fastleo:user {--admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new user';

    /**
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

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
        $user = new \App\Models\User;
        $fillables = $this->fillable;

        foreach ($fillables as $k => $fillable) {
            if ($fillable == 'password') {
                $user->password = Hash::make($this->secret(($k + 1) . "/" . count($fillables) . " User $fillable"));
            } else {
                $user->$fillable = $this->ask(($k + 1) . "/" . count($fillables) . " User $fillable");
            }
        }

        $options = $this->options();
        if ($options['admin']) {
            $user->fastleo_admin = true;
        }

        $user->save();

        $this->info("User created (id: {$user->id})");
        if ($options['admin']) {
            $this->info("User status admin");
        }

        return true;
    }
}
