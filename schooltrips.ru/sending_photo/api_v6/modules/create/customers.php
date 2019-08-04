<?php
/**
 * Создание покупателей
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class CustomersCreate extends EntityCreate
{
    public function __construct($entity, \Amo\CRM $instance)
    {
		parent::__construct($entity, $instance);
		$this->fields([
			'name' => 'Новый покупатель',
			'next_date' => strtotime('NOW +1 month'),
			'custom_fields' => []
		]);
	}
	
    /**
     * Create entity
	 * @return Entity
     */
    public function entity()
    {
		return new CustomersEntity($this->_fields, 'customers', $this->_instance);
	}
}
