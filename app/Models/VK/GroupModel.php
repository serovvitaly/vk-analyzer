<?php

namespace App\Models\VK;

use App\Models\VkBaseModel;

class GroupModel extends VkBaseModel
{
    protected $table = 'vk_groups';

    const SOURCE_VK = 'vk';

    public $fillable = ['group_id', 'screen_name'];
}