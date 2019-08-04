<?php
/**
 * Получение покупателей
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class CustomersGet extends EntityList
{
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct($instance);
		$this->entity('customers');
	}
	
    /**
     * Получние по ID
     */
    public function fromId($id)
    {
		if (!$customers = $this->filter('id', $id)->run()) {
			return null;
		}
		if (is_array($id)) {
			return $customers;
		}
		return $customers[0];
	}
	
    /**
     * Поиск по странице выборки
     */
    public function page($val)
    {
		return $this->param('PAGEN_1', $val);
	}
	
    /**
     * Получение данных
     */
    public function run()
    {
		if ($values = parent::run()) {
			
			$customers = array();
			foreach ($values as $value) {
				
				$customers[]= new CustomersEntity($value, 'customers', $this->_instance);
			}
			return $customers;
		}
		return false;
	}
}
