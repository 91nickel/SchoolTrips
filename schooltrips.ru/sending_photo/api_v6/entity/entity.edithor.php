<?php
/**
 * Класс редактирования сущности
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class EntityEdithor
{
    protected $_instance,
			  $entity;
	
    public function __construct($entity, \Amo\CRM $instance)
    {
		$this->_instance = $instance;
		$this->entity = $entity;
	}
	
    /**
     * Write RAW data
	 * @param $key string field name
	 * @param $val mixed field val
	 * @return EntityEdithor
     */
    public function field($key, $val)
    {
		$this->entity->field($key, $val);
		return $this;
	}
}
