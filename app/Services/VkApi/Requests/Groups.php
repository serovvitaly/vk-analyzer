<?php
/**
 *
 */

namespace App\Services\VkApi\Requests;

use App\Services\VkApi\Request;

class Groups extends Request
{

    /**
     * Возвращает информацию о заданном сообществе или о нескольких сообществах.
     * @see https://vk.com/dev/groups.getById
     * @param bool $only_first - возвращает только первую группу из результата
     * @return \App\Services\VkApi\Objects\Group | array
     * @throws \App\Services\VkApi\RequestException
     */
    public function getById($only_first = false)
    {
        $this->setMethodName('groups.getById');

        $this->setAvailableParams(['group_ids','group_id','fields']);

        $this->exec();

        $response_json = $this->getResponse()->getJson();

        if (empty($response_json)) {

            return null;
        }

        /**
         * Внутренний хелпер, для создания объекта группы VK
         * @param $group_mix
         * @return $this
         */
        $prepare_group_obj = function($group_mix){

            $group = (new \App\Services\VkApi\Objects\Group)
                ->setId($group_mix->id)
                ->setName($group_mix->name)
                ->setScreenName($group_mix->screen_name)
                ->setIsClosed($group_mix->is_closed)
                ->setType($group_mix->type)
                ->setPhoto50($group_mix->photo_50)
                ->setPhoto100($group_mix->photo_100)
                ->setPhoto200($group_mix->photo_200);

            return $group;
        };

        if ($only_first) {

            return $prepare_group_obj($response_json[0]);
        }

        $groups_arr = [];

        foreach ($response_json as $group_mix) {

            $groups_arr[] = $prepare_group_obj($group_mix);
        }

        return $groups_arr;
    }

    /**
     * Возвращает список участников сообщества.
     * @see https://vk.com/dev/groups.getMembers
     */
    public function getMembers()
    {
        $this->setMethodName('groups.getMembers');

        $this->setAvailableParams(['group_id','sort','offset','count','fields','filter']);

        $this->setRequiredParams('group_id');

        $this->exec();

        $response_json = $this->getResponse()->getJson();

        if (empty($response_json)) {

            //
        }

        $members_count = $response_json->count;
        $members_items = $response_json->items;
    }

}