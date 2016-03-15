<?php

namespace App\Console\Commands;

use App\Services\VkApi\ListIterator;
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

    public function _go()
    {

        $this->info(microtime());
        usleep(300000);
        $this->info(microtime());


        return;

        $users = \App\Models\VK\UserModel::whereNull('posts_count')->take(1)->get();

        print_r($users);

        return;

        $posts_list_iterator = \App\Services\VkApi\Requests\Wall::instance()
            ->set('owner_id', 21117624)
            ->set('filter', 'others')
            ->set('count', 2)
            ->set('offset', 5)
            ->get();
    }

    public function _get()
    {
        $owner_id = '-' . $this->argument('param1');

        $job = (new \App\Jobs\VkApi\TakeWall($owner_id));

        $this->dispatch($job);
    }
}
