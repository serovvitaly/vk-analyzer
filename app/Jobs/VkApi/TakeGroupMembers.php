<?php

namespace App\Jobs\VkApi;

use App\Jobs\Job;
use App\Models\VK\UserModel;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mockery\CountValidator\Exception;

class TakeGroupMembers extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    protected $group_id;
    protected $offset;

    /**
     * Create a new job instance.
     * @param $group_id
     * @param int $offset
     */
    public function __construct($group_id, $offset = 0)
    {
        $group_id = (int) $group_id;

        if ($group_id < 0) {

            $group_id = $group_id * -1;
        }

        $this->group_id = $group_id;

        $this->offset = $offset;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $max_count_for_list = 1000;

        \Log::info("group_id: {$this->group_id}, offset: {$this->offset}");

        $members_ids_list_iterator = \App\Services\VkApi\Requests\Groups::instance()
            ->set('group_id', $this->group_id)
            ->set('offset', $this->offset)
            ->set('count', $max_count_for_list)
            ->set('fields', 'verified, sex, bdate, city, country, photo_id, photo_50, photo_100, photo_200_orig, photo_200, photo_400_orig, photo_max, photo_max_orig, online, online_mobile, lists, domain, has_mobile, contacts, connections, site, education, universities, schools, can_post, can_see_all_posts, can_see_audio, can_write_private_message, status, last_seen, relation, relatives, counters')
            ->getMembers();

        if ( ! count($members_ids_list_iterator) ) {

            return;
        }

        /**
         * @var \App\Services\VkApi\Objects\User $user_obj
         */
        foreach ($members_ids_list_iterator as $user_obj) {

            $user_model = UserModel::firstOrCreate(['user_id' => $user_obj->getId()]);

            $user_model->user_id = $user_obj->getId();
            $user_model->first_name = $user_obj->getFirstName();
            $user_model->last_name = $user_obj->getLastName();
            $user_model->sex = $user_obj->getSex();
            //$user_model->domain = $user_obj->getDomain();
            $user_model->bdate = $user_obj->getBdate();
            $user_model->city = $user_obj->getCity();
            $user_model->country = $user_obj->getCountry();
            $user_model->hidden = $user_obj->getHidden();
            $user_model->verified = $user_obj->getVerified();
            $user_model->can_post = $user_obj->getCanPost();
            $user_model->can_see_all_posts = $user_obj->getCanSeeAllPosts();
            $user_model->can_write_private_message = $user_obj->getCanWritePrivateMessage();

            $user_model->save();

            \DB::insert('insert ignore into '.\CreateVkTables::TABLE_USERS_GROUPS.' (user_id, group_id) values (?, ?)', [
                $user_obj->getId(), 
                $this->group_id
            ]);

            $last_seen = $user_obj->getLastSeen();
            if ( ! empty($last_seen) ) {
                $last_seen = json_decode($last_seen);
                \DB::insert('insert ignore into vk_users_last_seen (user_id, time, platform, created_at) values (?, ?, ?, ?)', [
                    $user_obj->getId(),
                    date('Y-m-d H:i:s', $last_seen->time),
                    \App\Services\VkApi\Object::getFromObj($last_seen, 'platform'),
                    date('Y-m-d H:i:s')
                ]);
            }
        }

        if ( $members_ids_list_iterator->getTotalCount() <=  $this->offset + $max_count_for_list ) {
            return;
        }
        
        $offset = $this->offset + $max_count_for_list;

        $job = (new \App\Jobs\VkApi\TakeGroupMembers($this->group_id, $offset))->delay(1);
        $this->dispatch($job);
    }
}
