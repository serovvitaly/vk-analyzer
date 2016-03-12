<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class VkApiWallCommand extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vk:wall {method=list} {param1?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
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
        $method_name = '_' . $this->argument('method');

        if ( ! method_exists($this, $method_name) ) {
            $this->warn('Метод не известен - ' . $this->argument('method'));
            return;
        }

        $this->$method_name();
    }

    public function _get()
    {
        $owner_id = '-' . $this->argument('param1');

        $job = (new \App\Jobs\VkApi\TakeWall($owner_id));

        $this->dispatch($job);
    }
}
