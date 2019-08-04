<?php
/**
 * Ответ сервера
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class Response
{
    private $code,
			$data,
			$error = '';

    public function __construct($data, $code, $account)
    {
		if (!in_array($code, [200, 201, 202, 204])) {
			throw new \Exception('AmoCRM api request error. Code: '.$code.'. Domain: '.$account->domain);	
		}
        $this->code = $code;
        $this->data = json_decode($data);
    }

    /**
     * Данные ответа сервера
     */
    public function getData()
    {
        return $this->data;
    }
	
    /**
     * Код ответа сервера
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Текст ответа сервера
     */
    public function getResp()
    {
        if (isset($this->data->response->error)) {
			$this->error = $this->data->response->error;
			return false;
		}
        if (isset($this->data->response)) {
			return $this->data->response;
		}
        return $this->data;
    }

    /**
     * Просмотр ошибки
     */
    public function getError()
    {
        return $this->error;
    }
}