<?php
/**
 * 
 */

namespace App\Services\VkApi;


class Response
{
    protected $response_content;

    protected $response_json;

    protected $is_list = false;

    /**
     * Общее количество элеметов в списке
     * @var int
     */
    protected $list_count;

    /**
     * Количество полученных элементов списка
     * @var
     */
    protected $items_count;

    public function __construct($response_content)
    {
        $this->response_content = trim( (string) $response_content );

        $this->response_json = json_decode($response_content);

        $this->checkResponseOnErrors($this->response_json);

        $response = $this->getJson(true);

        if (is_object($response) and property_exists($response, 'count') and property_exists($response, 'items')) {

            $this->is_list = true;

            $this->list_count = (int) $response->count;

            $this->items_count = count($response->items);
        }
    }

    /**
     * Возвращает JSON результата запроса
     * @param bool $only_response_field
     * @return mixed
     */
    public function getJson($only_response_field = true)
    {
        if ($only_response_field and property_exists($this->response_json, 'response')) {

            return $this->response_json->response;
        }

        return $this->response_json;
    }

    public function isList()
    {
        return $this->is_list;
    }

    public function getListCount()
    {
        return $this->list_count;
    }

    public function getItemsCount()
    {
        return $this->items_count;
    }

    protected function checkResponseOnErrors($response_json)
    {
        if ( ! is_object($response_json) ) {

            throw new ResponseException('Response json must by a object');
        }

        if ( property_exists($response_json, 'error') ) {

            /**
             * TODO: нужно правильно получить параметры ошибки, использовать property_exists
             */
            $error_msg  = $response_json->error->error_msg;
            $error_code = $response_json->error->error_code;

            $error_msg = 'VK_API_ERROR: ' . $error_msg;

            throw new ResponseException($error_msg, $error_code);
        }
    }

    public function appendResponseToList($response_content)
    {
        $response_content = trim( (string) $response_content );

        $response_json = json_decode($response_content);

        $this->checkResponseOnErrors($response_json);

        if ( ! property_exists($response_json, 'response') ) {

            return;
        }

        $response_json = $response_json->response;

        $items = & $this->getJson(true)->items;

        foreach ($response_json->items as $item) {

            $items[] = $item;
        }
    }
}