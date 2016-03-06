<?php
/**
 * Базовый класс для всех объектов VK API
 */

namespace App\Services\VkApi;


abstract class Object
{
    /**
     * Взято ли из кэша
     * @var bool
     */
    protected $is_from_cache = false;

    /**
     * Дата объекта из кэша
     * @var \DateTime
     * TODO: нужно определиться в каком формате выдавать дату и время, стоит ли использовать DateTime
     */
    protected $cache_date;

    /**
     * @return \DateTime
     */
    public function getCacheDate()
    {
        if ( empty($this->cache_date) ) {

            return new \DateTime();
        }

        return new \DateTime($this->cache_date);
    }

    public function isFromCache()
    {
        return $this->is_from_cache;
    }
}