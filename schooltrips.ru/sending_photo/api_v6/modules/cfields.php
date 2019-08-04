<?php
/**
 * Получение информации о доп.полях
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class Cfields
{
    private $_instance,
			$fields = array();

    public function __construct(\Amo\CRM $instance)
	{
		$this->_instance = $instance;
		$current = $this->_instance->getCurrent();
		
		foreach ($current->custom_fields as $cf_entity=>$cfields) {
			
			foreach ($cfields as $cfield) {
				
				$this->fields[$cf_entity][$cfield->id] = $cfield;
			}
		}
    }
	
    /**
     * Получение полей сущности
	 * @param string $entity contacts|leads|companies|customers|{catalog id's}
     */
    public function fromEntity($entity)
    {
		if (!isset($this->fields[$entity])) {
			return array();
		}
        return $this->fields[$entity];
    }
}
