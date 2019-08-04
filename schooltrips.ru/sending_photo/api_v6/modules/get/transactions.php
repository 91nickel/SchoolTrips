<?php
/**
 * Получение покупок
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class TransactionsGet extends EntityList
{
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct($instance);
		$this->entity('transactions');
	}
	
    /**
     * Получние по ID
     */
    public function fromId($id)
    {
		if (!$transactions = $this->filter('id', $id)->run()) {
			return null;
		}
		return $transactions[0];
	}
	
    /**
     * Получние по ID покупателя
     */
    public function fromCustomerId($id)
    {
		if (!$transactions = $this->filter('customer_id', $id)->run()) {
			return null;
		}
		return $transactions;
	}
	
    /**
     * Получение данных
     */
    public function run()
    {
		if ($values = parent::run()) {
			
			$transactions = array();
			foreach ($values as $value) {
				
				$transactions[]= new TransactionsEntity('transactions', $value, $this->_instance);
			}
			return $transactions;
		}
		return false;
	}
}
