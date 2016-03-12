<?php

namespace App\Jobs\VkApi;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TakeGroup extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $group_id_or_screen_name;

    /**
     * Create a new job instance.
     *
     */
    public function __construct($group_id_or_screen_name)
    {
        $this->group_id_or_screen_name = $group_id_or_screen_name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * @var \App\Services\VkApi\Objects\Group $group
         */
        $group = \App\Services\VkApi\Requests\Groups::instance()
            ->set('group_id', $this->group_id_or_screen_name)
            ->getById(true);

        if (is_numeric($this->group_id_or_screen_name)) {
            $vk_group_model = \App\Models\VK\GroupModel::firstOrNew(['group_id' => $group->getId()]);
            $vk_group_model->screen_name = $group->getScreenName();
        } else {
            $vk_group_model = \App\Models\VK\GroupModel::firstOrNew(['screen_name' => $group->getScreenName()]);
            $vk_group_model->group_id = $group->getId();
        }

        $vk_group_model->name = $group->getName();

        $vk_group_model->save();
    }
}
