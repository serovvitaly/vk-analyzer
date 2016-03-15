<?php

namespace App\Jobs\VkApi;

use App\Jobs\Job;
use App\Services\VkApi\ListIterator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TakeWall extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    protected $owner_id;

    protected $offset;

    /**
     * Create a new job instance.
     * @param $owner_id
     * @param int $offset
     */
    public function __construct($owner_id, $offset = 0)
    {
        $this->owner_id = $owner_id;

        $this->offset = (int) $offset;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * @var ListIterator $posts_list_iterator
         */
        $posts_list_iterator = \App\Services\VkApi\Requests\Wall::instance()
            ->set('owner_id', $this->owner_id)
            ->set('count', ListIterator::MAX_COUNT_FOR_LIST)
            ->set('offset', $this->offset)
            ->get();

        if ( ! count($posts_list_iterator) ) {

            return;
        }

        foreach ($posts_list_iterator as $post_obj) {
            /**
             * @var \App\Services\VkApi\Objects\Post $post_obj
             */
            $post_model = \App\Models\VK\PostModel::firstOrCreate(['post_id' => $post_obj->getId()]);
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
        }

        /**
         * Если достигнут конец выборки, то прекращаем задание
         */
        if ( $posts_list_iterator->getTotalCount() <=  $this->offset + ListIterator::MAX_COUNT_FOR_LIST ) {
            return;
        }

        /**
         * Смещаем сдвиг выборки
         */
        $offset = $this->offset + ListIterator::MAX_COUNT_FOR_LIST;

        /**
         * Запускаем новое задание
         */
        $job = (new \App\Jobs\VkApi\TakeWall($this->owner_id, $offset))->delay( rand(1,3) );
        $this->dispatch($job);
    }
}