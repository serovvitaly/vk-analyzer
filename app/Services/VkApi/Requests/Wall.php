<?php
/**
 *
 */

namespace App\Services\VkApi\Requests;

use App\Services\VkApi\ListIterator;
use App\Services\VkApi\Objects\Comment;
use App\Services\VkApi\Objects\Post;
use App\Services\VkApi\Request;

class Wall extends Request
{
    /**
     * Возвращает список записей со стены пользователя или сообщества.
     * @see https://vk.com/dev/wall.get
     * @return ListIterator
     */
    public function get()
    {
        $this->setMethodName('wall.get');

        $this->setAvailableParams(['owner_id','domain','offset','count','filter','extended','fields']);

        $this->setRequiredParams('owner_id');

        $this->exec();

        $response_json = $this->getResponse()->getJson();

        $list_iterator = new ListIterator;

        $list_iterator->setSourceJson($response_json);

        $list_iterator->setTotalCount($response_json->count);

        foreach ($response_json->items as $item_json_obj) {

            $list_iterator[] = Post::makeFromJson($item_json_obj);
        }

        return $list_iterator;
    }

    /**
     * Метод, позволяющий осуществлять поиск по стенам пользователей или сообществ.
     * @see https://vk.com/dev/wall.search
     * @return ListIterator
     */
    public function search()
    {
        $this->setMethodName('wall.search');

        $this->setAvailableParams(['owner_id','domain','query','owners_only','offset','count','extended','fields']);

        $this->setRequiredParams(['query']);

        $this->exec();

        $response_json = $this->getResponse()->getJson();

        $list_iterator = new ListIterator;

        $list_iterator->setSourceJson($response_json);

        $list_iterator->setTotalCount($response_json->count);

        foreach ($response_json->items as $item_json_obj) {

            $list_iterator[] = Post::makeFromJson($item_json_obj);
        }

        return $list_iterator;
    }

    /**
     * Позволяет получать список репостов заданной записи.
     * @see https://vk.com/dev/wall.getReposts
     * @return ListIterator
     */
    public function getReposts()
    {
        $this->setMethodName('wall.getReposts');

        $this->setAvailableParams(['owner_id','post_id','offset','count','need_likes']);

        $this->setRequiredParams(['owner_id', 'post_id']);

        $this->exec();
    }

    /**
     * Возвращает список комментариев к записи на стене.
     * @see https://vk.com/dev/wall.getComments
     * @return ListIterator
     * @throws \App\Services\VkApi\RequestException
     */
    public function getComments()
    {
        $this->setMethodName('wall.getComments');

        $this->setAvailableParams(['owner_id','post_id','offset','count','need_likes']);

        $this->setRequiredParams(['owner_id', 'post_id']);

        $this->exec();
        
        $list_iterator = new ListIterator;

        $response_json = $this->getResponse()->getJson();

        $list_iterator->setSourceJson($response_json);

        $list_iterator->setTotalCount($response_json->count);

        foreach ($response_json->items as $item_json_obj) {

            if ( ! property_exists($item_json_obj, 'post_id') ) {

                $item_json_obj->post_id = $this->getParam('post_id');
            }

            $list_iterator[] = Comment::makeFromJson($item_json_obj);
        }

        return $list_iterator;
    }
}