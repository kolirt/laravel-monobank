<?php

namespace Kolirt\Monobank\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monobank:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Instalation Openstreetmap package';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('vendor:publish', ['--provider' => 'Kolirt\\Monobank\\ServiceProvider']);
    }
}
