<?php

namespace Fastleo\Fastleo;

use Illuminate\Support\Facades\Hash;
use Illuminate\Console\Command;

class FastleoAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fastleo:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new admin';

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
        $user = new \App\User;
        $fillables = $this->fillable;

        foreach ($fillables as $k => $fillable) {
            if ($fillable == 'password') {
                $user->password = Hash::make($this->secret(($k + 1) . "/" . count($fillables) . " User $fillable"));
            } else {
                $user->$fillable = $this->ask(($k + 1) . "/" . count($fillables) . " User $fillable");
            }
        }

        $user->admin = true;

        if ($this->confirm("Do you want to create the user?", true)) {
            $user->save();
            $this->info("User created (id: {$user->id})");
        }

        return true;
    }
}
