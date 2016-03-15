<?php

namespace App\Models\VK;

use App\Models\VkBaseModel;

class UserModel extends VkBaseModel
{
    public $table = \CreateVkTables::TABLE_USERS;

    public $fillable = ['user_id'];
}
