<?php

namespace App\Jobs\VkApi;

use App\Jobs\Job;
use App\Models\VK\PostModel;
use App\Services\VkApi\ListIterator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TakePostComments extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    protected $owner_id;
    protected $post_id;
    protected $offset;

    /**
     * Create a new job instance.
     * @param $owner_id
     * @param $post_id
     * @param int $offset
     */
    public function __construct($owner_id, $post_id, $offset = 0)
    {
        $this->owner_id = (int) $owner_id;
        $this->post_id = (int) $post_id;
        $this->offset = (int) $offset;
    }

    /**
     * Execute the job.
     *
     */
    public function handle()
    {
        /**
         * @var ListIterator $comments_list_iterator
         */

        try {

            $comments_list_iterator = \App\Services\VkApi\Requests\Wall::instance()
                ->set('owner_id', $this->owner_id)
                ->set('post_id', $this->post_id)
                ->set('count', 100)
                ->set('offset', $this->offset)
                ->set('need_likes', 1)
                ->getComments();

        }
        catch (\App\Services\VkApi\ResponseException $exception) {

            switch ($exception->getCode()) {
                case 15:
                    $post = PostModel::findOrFail($this->post_id);
                    $post->is_deleted = 1;
                    \Log::info('Post is deleted, post_id = ' . $this->post_id);
                    return;
            }

            throw $exception;
        }

    //    \Log::info('Загрузка комментов 22: owner_id = '.$this->owner_id.'; post_id = '.$this->post_id.'; TotalCount = '.$comments_list_iterator->getTotalCount()
    //        .'; offset = '.$this->offset.'; items_count = ' .count($comments_list_iterator). '');

        if ( ! count($comments_list_iterator) ) {

            return;
        }

        foreach ($comments_list_iterator as $comment_obj) {
            /**
             * @var \App\Services\VkApi\Objects\Comment $comment_obj
             */
            //$comment_model = \App\Models\VK\CommentModel::firstOrCreate(['owner_id' => $comment_obj->getOwnerId(), 'comment_id' => $comment_obj->getCommentId()]);

            $comment_model = \App\Models\VK\CommentModel::where('post_id', '=', $comment_obj->getPostId())
                ->where('comment_id', '=', $comment_obj->getCommentId())
                ->first();

            if (! $comment_model) {
                $comment_model = new \App\Models\VK\CommentModel;
            }

            $comment_model->comment_id = $comment_obj->getCommentId();
            $comment_model->post_id = $comment_obj->getPostId();
            $comment_model->from_id = $comment_obj->getFromId();
            $comment_model->date = date('Y-m-d H:i:s', $comment_obj->getDate());
            $comment_model->text = $comment_obj->getText();
            $comment_model->likes = $comment_obj->getLikes();
            $comment_model->reply_to_user = $comment_obj->getReplyToUser();
            $comment_model->reply_to_comment = $comment_obj->getReplyToComment();
            $comment_model->is_has_attachments = $comment_obj->getIsHasAttachments();

            $comment_model->save();
        }

        /**
         * Если достигнут конец выборки, то прекращаем задание
         */
        if ( $comments_list_iterator->getTotalCount() <=  $this->offset + ListIterator::MAX_COUNT_FOR_LIST ) {
            return;
        }

        /**
         * Смещаем сдвиг выборки
         */
        $offset = $this->offset + ListIterator::MAX_COUNT_FOR_LIST;

        /**
         * Запускаем новое задание
         */
        $job = (new \App\Jobs\VkApi\TakePostComments($this->owner_id, $this->post_id, $offset))->delay(1);
        $this->dispatch($job);
    }
}
