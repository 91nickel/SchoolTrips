<?php
/**
 * Создание элементов каталогов
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class Catalog_elementsCreate extends EntityCreate
{
    public function __construct($entity, \Amo\CRM $instance)
    {
		parent::__construct($entity, $instance);
		$this->fields([
			'name' => 'Новый элемент каталога',
			'catalog_id' => 0,
		]);
	}
	
    /**
     * Create entity
	 * @return Entity
     */
    public function entity()
    {
		return new Catalog_elementsEntity($this->_fields, $this->_fields->catalog_id, $this->_instance);
	}
}
