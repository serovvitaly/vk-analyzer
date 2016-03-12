<?php

namespace App\Models\VK;

use App\Models\VkBaseModel;

class CommentModel extends VkBaseModel
{
    protected $table = 'vk_comments';

    public $fillable = ['owner_id', 'post_id'];
}
