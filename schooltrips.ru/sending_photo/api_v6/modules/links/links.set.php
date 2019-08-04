<?php
/**
 * Связи каталогов amoCRM - установка
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class LinksSet
{
	protected $link = [],
			  $unlink = [],
			  $_instance,
			  $entity,
			  $error;

    public function __construct(\Amo\CRM $instance)
    {
		$this->_instance = $instance;
	}
	
    /**
     * Установка связи
	 * @param array $link
	 * @return LinksSet
     */
    public function link($link)
    {
		$key = $link['from_id'].'-'.$link['to_id'];
		$this->link[$key] = $link;
		return $this;
	}
	
    /**
     * Разрыв связи
	 * @param array $link
	 * @return LinksSet
     */
    public function unlink($link)
    {
		$key = $link['from_id'].'-'.$link['to_id'];
		$this->unlink[$key] = $link;
		return $this;
	}
	
    /**
     * Подготовка данных
	 * @return array
     */
    public function bind()
    {
		$links = [];
		if ($link = array_values($this->link)) {
			$links['link'] = $link;
		}
		if ($unlink = array_values($this->unlink)) {
			$links['unlink'] = $unlink;
		}
		return $links;
	}
	
    /**
     * Выполнение запроса
	 * @return false|object
     */
    public function run()
    {
		$data = [];
		$data['request']['links'] = $this->bind();
		$result = $this->_instance->query('post', '/private/api/v2/json/links/set', $data);
		$response = $result->getResp();
		if (!$response) {
			$this->error = (object)array(
				'code' => $result->getCode(),
				'text' => $result->getError(),
			);
			return false;
		}
		return $response;
	}
	
    /**
     * Информация об ошибках
	 * @return null|object
     */
    public function getError()
    {
		return $this->error;
	}
}
