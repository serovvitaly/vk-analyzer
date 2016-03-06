<?php
/**
 * Объект group, описывающий сообщество
 * @see https://vk.com/dev/fields_groups
 */

namespace App\Services\VkApi\Objects;

use App\Services\VkApi\Object;

class Group extends Object
{
    protected $id;
    protected $name;
    protected $screen_name;
    protected $is_closed;
    protected $type;
    protected $photo_50;
    protected $photo_100;
    protected $photo_200;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int) $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getScreenName()
    {
        return $this->screen_name;
    }

    /**
     * @param mixed $screen_name
     * @return $this
     */
    public function setScreenName($screen_name)
    {
        $this->screen_name = $screen_name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsClosed()
    {
        return $this->is_closed;
    }

    /**
     * @param mixed $is_closed
     * @return $this
     */
    public function setIsClosed($is_closed)
    {
        $this->is_closed = $is_closed;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhoto50()
    {
        return $this->photo_50;
    }

    /**
     * @param mixed $photo_50
     * @return $this
     */
    public function setPhoto50($photo_50)
    {
        $this->photo_50 = $photo_50;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhoto100()
    {
        return $this->photo_100;
    }

    /**
     * @param mixed $photo_100
     * @return $this
     */
    public function setPhoto100($photo_100)
    {
        $this->photo_100 = $photo_100;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhoto200()
    {
        return $this->photo_200;
    }

    /**
     * @param mixed $photo_200
     * @return $this
     */
    public function setPhoto200($photo_200)
    {
        $this->photo_200 = $photo_200;

        return $this;
    }

    public function getPosts()
    {
        $owner_id = '-' . $this->getId();

        $posts = \App\Services\VkApi\Requests\Wall::instance()
            ->set('owner_id', $owner_id)
            ->set('count', 100)
            ->get();

        return $posts;
    }
}