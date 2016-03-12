<?php
/**
 * Объект post, описывающий запись на стене пользователя или сообщества
 * @see https://vk.com/dev/post
 */

namespace App\Services\VkApi\Objects;

use App\Services\VkApi\Object;

class Post extends Object
{
    protected $id;              // идентификатор записи
    protected $owner_id;        // идентификатор владельца стены, на которой размещена запись
    protected $from_id;         // идентификатор автора записи
    protected $date;            // время публикации записи в формате unixtime
    protected $text;            // текст записи
    protected $reply_owner_id;  // идентификатор владельца записи, в ответ на которую была оставлена текущая
    protected $reply_post_id;   // идентификатор записи, в ответ на которую была оставлена текущая
    protected $comments;        // информация о комментариях к записи, объект с полями: count — количество комментариев; can_post* — информация о том, может ли текущий пользователь комментировать запись
    protected $likes;           // информация о лайках к записи, объект с полями: count — число пользователей, которым понравилась запись
    protected $reposts;         // информация о репостах записи («Рассказать друзьям»), объект с полями: count — число пользователей, скопировавших запись
    protected $post_type;       // тип записи, может принимать следующие значения: post, copy, reply, postpone, suggest
    protected $post_source;     // информация о способе размещения записи
    protected $signer_id;       // идентификатор автора, если запись была опубликована от имени сообщества и подписана пользователем

    /**
     * @param $json_obj
     * @return Post
     */
    public static function makeFromJson( \stdClass $json_obj )
    {
        $post_obj = new self;

        $post_obj->set('id', intval($json_obj->id));
        $post_obj->set('owner_id', $json_obj->owner_id);
        $post_obj->set('from_id', $json_obj->from_id);
        $post_obj->set('date', intval($json_obj->date));
        $post_obj->set('text', $json_obj->text);
        //$post_obj->set('reply_owner_id', $json_obj->reply_owner_id);
        //$post_obj->set('reply_post_id', $json_obj->reply_post_id);
        $post_obj->set('comments', $json_obj->comments->count);
        $post_obj->set('likes', $json_obj->likes->count);
        $post_obj->set('reposts', $json_obj->reposts->count);
        $post_obj->set('post_type', $json_obj->post_type);
        //$post_obj->set('post_source', json_decode($json_obj->post_source));
        //$post_obj->set('signer_id', $json_obj->signer_id);

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
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /**
     * @return mixed
     */
    public function getFromId()
    {
        return $this->from_id;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return mixed
     */
    public function getReplyOwnerId()
    {
        return $this->reply_owner_id;
    }

    /**
     * @return mixed
     */
    public function getReplyPostId()
    {
        return $this->reply_post_id;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return mixed
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @return mixed
     */
    public function getReposts()
    {
        return $this->reposts;
    }

    /**
     * @return mixed
     */
    public function getPostType()
    {
        return $this->post_type;
    }

    /**
     * @return mixed
     */
    public function getPostSource()
    {
        return $this->post_source;
    }

    /**
     * @return mixed
     */
    public function getSignerId()
    {
        return $this->signer_id;
    }
}