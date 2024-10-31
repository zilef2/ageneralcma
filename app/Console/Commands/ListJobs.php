<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;

class ListJobs extends Command
{
    protected $signature = 'jobs:list';
    protected $description = 'List all jobs in the queue';

    public function handle()
    {
        $jobs = Queue::all();
        $completedJobs = $jobs->filter(function ($job) {
            return $job->isCompleted();
        })->take(2); // Limitar a 2 trabajos finalizados

        $inProcessJobs = $jobs->filter(function ($job) {
            return !$job->isCompleted();
        });

        $this->info("Completed Jobs:");
        foreach ($completedJobs as $job) {
            $this->info($job->getName());
        }

        $this->info("\nIn Process Jobs:");
        foreach ($inProcessJobs as $job) {
            $this->info($job->getName());
        }
    }
}
