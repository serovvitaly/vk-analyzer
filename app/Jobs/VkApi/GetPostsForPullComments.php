<?php

namespace App\Jobs\VkApi;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GetPostsForPullComments extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $posts_obj_arr = \DB::table('vk_posts')
            ->leftJoin('vk_comments', 'vk_comments.post_id', '=', 'vk_posts.post_id')
            ->select(['vk_posts.post_id', 'vk_posts.owner_id', 'vk_posts.comments'])
            ->where('vk_posts.comments', '>', 0)
            ->whereNull('vk_posts.is_deleted')
            ->whereNull('vk_comments.comment_id')
            ->take(100)
            ->get();

        if (empty($posts_obj_arr)) {
            return;
        }

        $delay = 1;
        $total_delay = 1;

        foreach ($posts_obj_arr as $posts_obj) {

            $job = new TakePostComments($posts_obj->owner_id, $posts_obj->post_id);
            $job->delay($delay);
            dispatch($job);

            $delay = ceil( $posts_obj->comments / \App\Services\VkApi\ListIterator::MAX_COUNT_FOR_LIST );

            $total_delay += $delay;
        }

        $post_job = new GetPostsForPullComments();
        $post_job->delay($total_delay);
        dispatch($post_job);
    }
}
