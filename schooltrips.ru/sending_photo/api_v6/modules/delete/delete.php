<?php
/**
 * Удаление сущности
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class EntityDelete
{
    protected $_fields = array(),
			  $_instance,
			  $entity;
	
    public function __construct($entity, \Amo\CRM $instance)
    {
		$this->_instance = $instance;
		$this->entity = $entity;
	}
	
    /**
     * Delete entitys
	 * @return 
     */
    public function delete($entitys)
    {
		$ids = [];
		$data = [];
		foreach ($entitys as $entity) {
			$ids[]= $entity->raw()->id;
		}
		$data['request'][$this->entity]['delete'] = $ids;
		$result = $this->_instance->query('post', '/private/api/v2/json/'.$this->entity.'/set', $data);
		return $result->getResp();
	}
}
