<?php
/**
 * Создание каталогов
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class CatalogsCreate extends EntityCreate
{
    public function __construct($entity, \Amo\CRM $instance)
    {
		parent::__construct($entity, $instance);
		$this->fields([
			'name' => 'Новый каталог',
			'sort' => 10
		]);
	}
	
    /**
     * Create entity
	 * @return Entity
     */
    public function entity()
    {
		return new CatalogsEntity($this->_fields, $this->_instance);
	}
}
