<?php
/**
 *
 */

namespace App\Services\VkApi;

class Request
{
    protected $use_cache;

    /**
     * @var \App\Services\VkApi\Response
     */
    private $response;

    /**
     * Максимальное количество записей в одной выборке для списков
     * @var int
     */
    protected $max_count_for_list = 100;

    /**
     * Требуется ли access_token
     * @var bool
     */
    protected $is_required_access_token = true;

    protected $method_name;

    protected $params_arr = [
        'v' => '5.45'
    ];

    /**
     * Допустимые параметры для запроса
     * @var array
     */
    protected $available_params = ['v'];

    /**
     * Обязательные параметры для запроса
     * @var array
     */
    protected $required_params = [];

    public function __construct($use_cache = true)
    {
        $this->use_cache = (bool) $use_cache;
    }

    /**
     * @param bool $use_cache - использовать ли данные из кэша
     * @return Request
     */
    public static function instance($use_cache = true)
    {
        $class_name = get_called_class();

        return new $class_name($use_cache);
    }

    /**
     * Определяет или возвращает состояние, требуется ли access_token
     * @param null $setting
     * @return bool
     */
    public function isRequiredAccessToken($setting = null)
    {
        if ( ! is_null($setting) ) {

            $this->is_required_access_token = (bool) $setting;
        }

        return $this->is_required_access_token;
    }

    /**
     * Устанавливает параметр
     * @param $param_name
     * @param $param_value
     * @return $this
     */
    public function set($param_name, $param_value)
    {
        $param_name = trim( (string) $param_name );

        $this->params_arr[$param_name] = trim( (string) $param_value );

        return $this;
    }

    public function getParam($param_name)
    {
        if ( ! array_key_exists($param_name, $this->params_arr) ) {

            return null;
        }

        return $this->params_arr[$param_name];
    }

    /**
     * Выполняет запрос
     * @return $this
     * @throws RequestException
     */
    public function exec()
    {
        $this->doRequest();

        return $this;
    }

    protected function doRequest()
    {
        if ( empty($this->method_name) ) {

            throw new RequestException('Is not set Vk Api method name');
        }

        $lost_required_params_arr = array_diff($this->getRequiredParams(), array_keys($this->params_arr));

        if ( ! empty($lost_required_params_arr) ) {

            throw new RequestException('Is not set required parameters: ' . implode(',', $lost_required_params_arr));
        }

        $available_params_values_arr = array_intersect_key($this->params_arr, array_fill_keys($this->getAvailableParams(), 1));

        $url = 'https://api.vk.com/method/' . $this->method_name . '?' . http_build_query($available_params_values_arr);

        $response_content = file_get_contents($url);

        if ( ! ($this->response instanceof Response) or ! $this->response->isList()) {

            $this->response = new Response($response_content);

            return;
        }

        $this->response->appendResponseToList($response_content);
    }

    /**
     * Устанавливает имя метода Vk Api
     * @param $method_name
     * @return $this
     */
    public function setMethodName($method_name)
    {
        $this->method_name = trim( (string) $method_name );

        return $this;
    }

    /**
     * Устанавливает список допустимых параметров для запроса
     * @param $params
     * @return $this
     */
    public function setAvailableParams($params)
    {
        if ( ! is_array($params) ) {

            $params = explode(',', $params);
        }

        foreach ($params as $param) {

            $this->available_params[] = trim( (string) $param );
        }

        return $this;
    }

    /**
     * Возвращает список допустимых параметров для запроса
     * @return array
     */
    public function getAvailableParams()
    {
        return $this->available_params;
    }

    /**
     * Устанавливает список обязательных параметров для запроса
     * @param $params
     * @return $this
     */
    public function setRequiredParams($params)
    {
        if ( ! is_array($params) ) {

            $params = explode(',', $params);
        }

        foreach ($params as $param) {

            $this->required_params[] = trim( (string) $param );
        }

        return $this;
    }

    /**
     * Возвращает список обязательных параметров для запроса
     * @return array
     */
    public function getRequiredParams()
    {
        return $this->required_params;
    }

    /**
     * Возвращает объект результата запроса
     * @return Response
     * @throws RequestException
     */
    protected function getResponse()
    {
        if ( ! $this->response instanceof Response) {

            throw new RequestException('Is not set Response instance');
        }

        return $this->response;
    }

    /**
     * @return int
     */
    public function getMaxCountForList()
    {
        return $this->max_count_for_list;
    }

    /**
     * @param int $max_count_for_list
     * @return $this
     */
    public function setMaxCountForList($max_count_for_list)
    {
        $this->max_count_for_list = (int) $max_count_for_list;

        return $this;
    }
}