<?php
/**
 * Объект user, описывающий пользователя
 * @see https://vk.com/dev/fields
 */

namespace App\Services\VkApi\Objects;

use App\Services\VkApi\Object;

class User extends Object
{
    protected $id; //
    protected $first_name; //
    protected $last_name; //
    protected $sex; //
    protected $domain; //
    protected $bdate; //
    protected $city; //
    protected $country; //
    protected $hidden; //
    protected $verified; //
    protected $can_post; //
    protected $can_see_all_posts; //
    protected $can_write_private_message; //
    protected $last_seen; //

    public static function makeFromJson(\stdClass $json_obj)
    {
        $post_obj = new self;

        $post_obj->set('id', intval($json_obj->id));
        $post_obj->set('first_name', $json_obj->first_name);
        $post_obj->set('last_name', $json_obj->last_name);
        $post_obj->set('sex', $json_obj->sex);
        $post_obj->set('domain', $json_obj->domain);
        $post_obj->set('bdate', self::getFromObj($json_obj, 'bdate'));

        $city = self::getFromObj($json_obj, 'city');
        $country = self::getFromObj($json_obj, 'country');
        $last_seen = self::getFromObj($json_obj, 'last_seen');
        $post_obj->set('city', $city ? json_encode($city) : null);
        $post_obj->set('country', $country ? json_encode($country) : null);
        $post_obj->set('last_seen', $last_seen ? json_encode($last_seen) : null);

        $post_obj->set('hidden', self::getFromObj($json_obj, 'hidden'));
        $post_obj->set('verified', self::getFromObj($json_obj, 'verified'));
        $post_obj->set('can_post', self::getFromObj($json_obj, 'can_post'));
        $post_obj->set('can_see_all_posts', self::getFromObj($json_obj, 'can_see_all_posts'));
        $post_obj->set('can_write_private_message', self::getFromObj($json_obj, 'can_write_private_message'));

        return $post_obj;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @return mixed
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @return mixed
     */
    public function getBdate()
    {
        return $this->bdate;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return mixed
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * @return mixed
     */
    public function getVerified()
    {
        return $this->verified;
    }

    /**
     * @return mixed
     */
    public function getCanPost()
    {
        return $this->can_post;
    }

    /**
     * @return mixed
     */
    public function getCanSeeAllPosts()
    {
        return $this->can_see_all_posts;
    }

    /**
     * @return mixed
     */
    public function getCanWritePrivateMessage()
    {
        return $this->can_write_private_message;
    }

    /**
     * @return mixed
     */
    public function getLastSeen()
    {
        return $this->last_seen;
    }
}