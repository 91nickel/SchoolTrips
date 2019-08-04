<?php
/**
 * Класс сущности - Каталог
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class CatalogsEntity extends EntityEditable
{
	private $_elements;
	
    public function __construct($raw, \Amo\CRM $instance)
    {
		parent::__construct('catalogs', $raw, $instance);
	}
	
    /**
     * Get catalog elements
     */
    public function elements()
    {
		if (is_null($this->_elements)) {
			$this->_elements = $this->_instance->catalog_elements()->get()->catalogId($this->raw()->id)->run();
		}
        return $this->_elements;
    }
	
    /**
     * Add catalog element
     */
    public function createElement()
    {
		$entity = $this->_instance->catalog_elements()->create();
		$entity->edit()->field('catalog_id',$this->raw()->id);
		return $entity;
	}
}
