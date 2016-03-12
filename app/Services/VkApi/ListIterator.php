<?php
/**
 * Created by PhpStorm.
 * User: Vitaly
 * Date: 09.03.2016
 * Time: 22:37
 */

namespace App\Services\VkApi;

use ArrayIterator;

class ListIterator extends ArrayIterator
{
    const MAX_COUNT_FOR_LIST = 100;

    protected $total_count = 0;

    protected $source_json;

    /**
     * @return mixed
     */
    public function getSourceJson()
    {
        return $this->source_json;
    }

    /**
     * @param mixed $source_json
     */
    public function setSourceJson(\stdClass $source_json)
    {
        $this->source_json = $source_json;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->total_count;
    }

    /**
     * @param $total_count
     */
    public function setTotalCount($total_count)
    {
        $this->total_count = (int) $total_count;
    }
}