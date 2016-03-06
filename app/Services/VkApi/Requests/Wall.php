<?php
/**
 *
 */

namespace App\Services\VkApi\Requests;

use App\Services\VkApi\ListIterator;
use App\Services\VkApi\Request;

class Wall extends Request
{
    /**
     * Возвращает список записей со стены пользователя или сообщества.
     * @see https://vk.com/dev/wall.get
     */
    public function get()
    {
        $this->setMethodName('wall.get');

        $this->setAvailableParams(['owner_id','domain','offset','count','filter','extended','fields']);

        $this->setRequiredParams('owner_id');

        $this->exec();

        $response_json = $this->getResponse()->getJson();

        var_dump( count($response_json->items) );

        if (empty($response_json)) {

            //
        }

        $posts_arr = [];

        $members_count = $response_json->count;
        $members_items = $response_json->items;

        return $posts_arr;
    }
}