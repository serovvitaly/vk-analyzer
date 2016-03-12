<?php

namespace App\Models\VK;

use App\Models\VkBaseModel;

class PostModel extends VkBaseModel
{
    protected $table = 'vk_posts';

    public $fillable = ['post_id'];
}
