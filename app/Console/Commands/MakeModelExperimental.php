<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MakeModelExperimental extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'copy:um {folderName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $foldernan = $this->argument('folderName');
        
        Artisan::call('make:model', [
            'name' => $foldernan,
            '--all' => true,
        ]);
        Artisan::call('copy:f');
        
    }
}
