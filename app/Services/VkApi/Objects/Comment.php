<?php
/**
 * Объект comment, описывающий комментарий
 * @see https://vk.com/dev/comment_object
 */

namespace App\Services\VkApi\Objects;

use App\Services\VkApi\Object;

class Comment extends Object
{
    protected $comment_id;          // идентификатор комментария
    protected $post_id;             // идентификатор записи
    protected $from_id;             // идентификатор автора комментария
    protected $date;                // дата создания комментария
    protected $text;                // текст комментария
    protected $likes;               // количество лайков
    protected $reply_to_user;       // идентификатор пользователя или сообщества, в ответ которому оставлен текущий комментарий
    protected $reply_to_comment;    // идентификатор комментария, в ответ на который оставлен текущий
    protected $is_has_attachments = 0;  // флаг, имеются ли вложения

    /**
     * @param $json_obj
     * @return Post
     */
    public static function makeFromJson( \stdClass $json_obj )
    {
        $post_obj = new self;

        $post_obj->set('comment_id', intval($json_obj->id));
        $post_obj->set('post_id', intval($json_obj->post_id));
        $post_obj->set('from_id', intval($json_obj->from_id));
        $post_obj->set('date', intval($json_obj->date));
        $post_obj->set('text', trim($json_obj->text));

        if (property_exists($json_obj, 'reply_to_user')) {

            $post_obj->set('reply_to_user', intval($json_obj->reply_to_user));
        }

        if (property_exists($json_obj, 'reply_to_comment')) {

            $post_obj->set('reply_to_comment', intval($json_obj->reply_to_comment));
        }

        if (property_exists($json_obj, 'likes')) {

            $post_obj->set('likes', intval($json_obj->likes->count));
        }

        if (property_exists($json_obj, 'attachments')) {

            $post_obj->set('is_has_attachments', 1);
        }

        return $post_obj;
    }

    /**
     * @return mixed
     */
    public function getCommentId()
    {
        return $this->comment_id;
    }

    /**
     * @return mixed
     */
    public function getPostId()
    {
        return $this->post_id;
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
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @return mixed
     */
    public function getReplyToUser()
    {
        return $this->reply_to_user;
    }

    /**
     * @return mixed
     */
    public function getReplyToComment()
    {
        return $this->reply_to_comment;
    }
    
    /**
     * @return mixed
     */
    public function getIsHasAttachments()
    {
        return $this->is_has_attachments;
    }
}