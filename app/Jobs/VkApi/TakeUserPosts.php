<?php

namespace App\Jobs\VkApi;

use App\Jobs\Job;
use App\Models\VK\UserModel;
use App\Services\VkApi\ListIterator;
use App\Services\VkApi\Objects\Post;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TakeUserPosts extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user_id;

    /**
     * Create a new job instance.
     * @param $user_id
     */
    public function __construct($user_id)
    {
        $this->user_id = (int) $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $owner_id = $this->user_id;

        /**
         * @var ListIterator $posts_list_iterator
         */
        $posts_list_iterator = \App\Services\VkApi\Requests\Wall::instance()
            ->set('owner_id', $owner_id)
            ->set('count', 3)
            ->set('offset', 0)
            ->set('extended', 1)
            ->set('filter', 'owner')
            ->set('fields', 'last_seen')
            ->get();

        if (count($posts_list_iterator) < 1) {
            return;
        }

        $own_posts_count = 0;

        /**
         * @var Post $post_obj
         */
        foreach ($posts_list_iterator as $post_obj) {

            if ($owner_id !== $post_obj->getOwnerId()) {
                continue;
            }
            
            if ($post_obj->getIsRepost()) {
                continue;
            }

            $post_model = \App\Models\VK\PostModel::firstOrCreate([
                'owner_id' => $post_obj->getOwnerId(),
                'post_id' => $post_obj->getId()
            ]);
            $post_model->post_id = $post_obj->getId();
            $post_model->owner_id = $post_obj->getOwnerId();
            $post_model->from_id = $post_obj->getFromId();
            $post_model->date = date('Y-m-d H:i:s', $post_obj->getDate());
            $post_model->unixtime = $post_obj->getDate();
            $post_model->text = $post_obj->getText();
            $post_model->comments = $post_obj->getComments();
            $post_model->likes = $post_obj->getLikes();
            $post_model->reposts = $post_obj->getReposts();
            $post_model->post_type = $post_obj->getPostType();

            $post_model->save();

            $own_posts_count++;
        }

        $user_model = UserModel::where('user_id', '=', $owner_id)->first();
        if ($user_model) {
            $user_model->posts_count = $posts_list_iterator->getTotalCount();
            $user_model->own_posts_count = $own_posts_count;
            $user_model->save();
        }

        $source_json = $posts_list_iterator->getSourceJson();

        if (property_exists($source_json, 'profiles') and is_array($source_json->profiles) and count($source_json->profiles) > 0) {
            $owner_obj = $source_json->profiles[0];
            if (property_exists($owner_obj, 'id') and ($owner_obj->id == $owner_id) and property_exists($owner_obj, 'last_seen')) {
                $last_seen = $owner_obj->last_seen;
                \DB::insert('insert ignore into vk_users_last_seen (user_id, time, platform, created_at) values (?, ?, ?, ?)', [
                    $owner_id,
                    date('Y-m-d H:i:s', $last_seen->time),
                    \App\Services\VkApi\Object::getFromObj($last_seen, 'platform'),
                    date('Y-m-d H:i:s')
                ]);
            }
        }
    }
}
