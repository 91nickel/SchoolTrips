<?php
/**
 * amoCRM класс
 * Запросы к серверу
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class Query
{
    /**
     * Account domain zone
     */
    public $zone = 'ru';
    /**
     * log requests
     */
    public $logs = false;
    /**
     * Delay
     */
    public $delay = 0;
    /**
     * curl
     */
    private $curl;
    /**
     * LastModified
     */
    private $modif;
    /**
     * last time
     */
    private $last_time;

    //=======================================================

    public function __construct()
    {
        /**
         * start time
         */
        $this->last_time = microtime(1);
    }

    /**
     * init curl
     */
    private function curlInit()
    {
        if (!is_null($this->curl)) curl_close($this->curl);
        $this->curl = \curl_init();

        curl_setopt($this->curl, CURLOPT_USERAGENT, 'amoapi-v6.1');
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
    }

    /**
     * Формирование ссылки
     */
    private function createUrl($account, $url, $data = array())
    {
        if ($url == '/api/calls/add/') {
            $link = 'https://sip.amocrm.'.$this->zone . $url . '?key=' . $account->key . '&code=' . $account->code;
		} elseif (strpos($url, '/api/unsorted/') !== false) {
			$link = 'https://'.$account->domain.'.amocrm.'.$this->zone . $url . '?api_key='.$account->hash . '&login='.$account->login;
        } else {
            $link = 'https://' . $account->domain . '.amocrm.'.$this->zone . $url . '?USER_LOGIN=' . $account->login . '&USER_HASH=' . $account->hash . '&lang=ru';
            if (!empty($data) && !isset($data['request'])) {
                $link .= '&' . http_build_query($data);
            }
        }
		curl_setopt($this->curl, CURLOPT_COOKIEJAR, AMOTEMP.'/'.$account->domain.'_cookie.txt');
		curl_setopt($this->curl, CURLOPT_COOKIEFILE, AMOTEMP.'/'.$account->domain.'_cookie.txt');
        return $link;
    }

    /**
     * LastModified
     */
    public function setModified($time)
    {
        $datetime = new \DateTime(date('Y-m-d H:i:s', $time));
        $this->modif = gmdate("D, d M Y H:i:s", $datetime->getTimestamp());
        return $this->modif;
    }

    /**
     * Ответ сервера
     */
    private function response($start_time)
    {
		$sleep = 0;
		if ($this->delay > 0) {
			$sleep = $this->delay * 1000000;
			usleep($sleep);
			$this->delay = 0;
		}
        $resp = array(
			'sleep' => $sleep / 1000000,
            'data' => curl_exec($this->curl),
            'code' => curl_getinfo($this->curl, CURLINFO_HTTP_CODE),
			'execution_time' => microtime(1) - $start_time,
        );
		$resp['request_time'] = $resp['execution_time']-$resp['sleep'];
		if ($resp['execution_time'] < 1) {
			$this->delay = 1-$resp['execution_time'];
		}
        $this->last_time = microtime(1);
        return $resp;
    }

    /**
     * Получение времени запроса
     */
    private function getLatency()
    {
        return microtime(1) - $this->last_time;
    }

    /**
     * Логирование запросов
     */
    public function log($method, $domain, $url, $request, $response)
    {
        $data = array(
            $method . ' ' . $url,
			'Memory: ' .(memory_get_peak_usage(true)/1024/1024).' MiB',
            'Request: ' . print_r($request, 1),
            'Response: ' . print_r($response, 1)
        );
        if (!$this->logs) return false;
        Logger::log(implode("\r\n", $data), $domain . '_requests');
    }
	
    /**
     * GET запрос
     */
    public function get($account, $method, $args = array(), $modified = false)
    {
        $this->curlInit();
        $url = $this->createUrl($account, $method, $args);
		$start = microtime(1);

        curl_setopt($this->curl, CURLOPT_URL, $url);
        if ($modified) {
            $since = $this->setModified($modified);
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('if-modified-since: ' . $since));
            $args['if-modified-since'] = $since;
        }
        $response = $this->response($start);
        $this->log('GET', $account->domain, $url, $args, $response);

        return new Response($response['data'], $response['code'], $account);
    }

    /**
     * POST запрос
     */
    public function post($account, $method, $args, $modified)
    {
        $this->curlInit();
        $url = $this->createUrl($account, $method, $args);
		$start = microtime(1);
		
        curl_setopt($this->curl, CURLOPT_URL, $url);
        if (strpos($url, 'https://sip.amocrm.'.$this->zone) !== false) {
            curl_setopt($this->curl, CURLOPT_POST, TRUE);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($args));
        } else {
            curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($args));
        }
        $response = $this->response($start);
        $this->log('POST', $account->domain, $url, $args, $response);

        return new Response($response['data'], $response['code'], $account);
    }
}