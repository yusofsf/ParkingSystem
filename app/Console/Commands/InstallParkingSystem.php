<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class InstallParkingSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:ParkingSystem';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Parking System Project';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('creating .env, generate key and migrate');
        $cp = shell_exec('cp .env.example .env');
        $result = Process::timeout(120)->run('php artisan key:generate && php artisan migrate -–seed');

        if ($result->failed() || !$cp) {
            $this->error($result->errorOutput() || $cp);
        } else {
            $this->info('.env created, key generated and Db migrated');
        }

        $this->info('Building Assets');
        $result = Process::run('npm run build');

        if ($result->failed() ) {
            $this->error($result->errorOutput());
        } else {
            $this->info('Assets Built');
        }
    }
}
