<?php
/**
 * Создание сущности
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class EntityCreate
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
     * Set RAW entity fields
	 * @param $fields array field list
	 * @return EntityCreate
     */
    public function fields($fields = [])
    {
		$this->_fields = (object)$fields;
		return $this;
	}
	
    /**
     * Create entity object
	 * @return Entity
     */
    public function entity()
    {
		return new EntityEditable($this->_fields, $this->entity, $this->_instance);
	}
	
    /**
     * Create entitys
	 * @return 
     */
    public function create($entitys)
    {
        $p = 0;
        $i = 0;
        $parts = array();
        $results = array();

        foreach ($entitys as $k=>&$entity) {
			$entity->field('request_id', $k);
            $parts[$p][] = $entity->raw();
            if ($i == 300) {
                $i = 0;
                $p++;
            } else {
                $i++;
            }
        }
        foreach ($parts as $part) {
            if (!empty($part)) {
				$result = $this->_create($part);
				foreach ($result->{$this->entity}->add->{$this->entity} as $raw) {
					$entitys[$raw->request_id]->raw($raw);
				}
            }
        }
        return $entitys;
	}
	
    /**
     * Create entitys
	 * @return 
     */
    protected function _create($entitys)
    {
		$data = [];
		$data['request'][$this->entity]['add'] = $entitys;
		$result = $this->_instance->query('post', '/private/api/v2/json/'.$this->entity.'/set', $data);
		return $result->getResp();
	}
}
