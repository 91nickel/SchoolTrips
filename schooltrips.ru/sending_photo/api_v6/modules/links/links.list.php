<?php
/**
 * Связи каталогов amoCRM - получение
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class LinksList
{
	protected $request = [
				'from' => '', // leads, contacts, companies, customers
				'from_id' => 0,
				'to' => 'catalog_elements',
				'to_catalog_id' => 0,
			  ],
			  $_instance,
			  $entity,
			  $error;

    public function __construct(\Amo\CRM $instance)
    {
		$this->_instance = $instance;
	}
	
    /**
     * Сущность, к которой осуществленна привязка
	 * @param integer $id entity id
	 * @param string $entity (leads|contacts|companies|customers)
	 * @return LinksList
     */
    public function from($id, $entity = 'leads')
    {
		$this->request['from'] = $entity;
		$this->request['from_id'] = $id;
		return $this;
	}
	
    /**
     * Сущность, которая привязана
	 * @param integer $id entity id
	 * @param string $entity (catalog_elements)
	 * @return LinksList
     */
    public function to($id, $entity = 'catalog_elements')
    {
		$this->request['to'] = $entity;
		if (!is_null($id)) {
			$this->request['to_catalog_id'] = $id;
		}
		return $this;
	}

    /**
     * Выполнение запроса
	 * @return false|object
     */
    public function run()
    {
		$data = [];
		$data['links'] = [$this->request];
		$result = $this->_instance->query('get', '/private/api/v2/json/links/list', $data);
		$response = $result->getResp();
		if (!$response) {
			$this->error = (object)[
				'code' => $result->getCode(),
				'text' => $result->getError(),
			];
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
