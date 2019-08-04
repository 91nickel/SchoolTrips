<?php
/**
 * Класс сущности - Покупатель
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class CustomersEntity extends EntityCfEditable
{
	private 
		$_periods,
		$_transactions;
	
    public function __construct($raw, $cf_entity, \Amo\CRM $instance)
    {
		parent::__construct('customers', $raw, $cf_entity, $instance);
	}
	
    /**
     * Get periods
     */
    public function periods()
    {
		if (is_null($this->_periods)) {
			$this->_periods = $this->_instance->customers_periods->get()->id($this->raw()->period_id)->run();
		}
        return $this->_periods;
    }
	
    /**
     * Get transactions
     */
    public function transactions()
    {
		if (is_null($this->_transactions)) {
			$this->_transactions = $this->_instance->transactions->get()->fromCustomerId($this->raw()->id);
		}
        return $this->_transactions;
    }
	
    /**
     * Add transactions
     */
    public function addTransaction()
    {
		return $this->_instance->transactions->set()->customerId($this->raw()->id)->date(time());
    }
	
    /**
     * Get next date
	 * @param string $format Date format
	 * @param integer $type Date type (0,1)
	 * @return string
     */
	public function nextDate($format = 'j F, H:i', $type = 0)
	{
		return monthRU(date($format, $this->raw->next_date), $type);
	}
	
    /**
     * Get task last date
	 * @param string $format Date format
	 * @param integer $type Date type (0,1)
	 * @return string
     */
	public function taskLastDate($format = 'j F, H:i', $type = 0)
	{
		return monthRU(date($format, $this->raw->task_last_date), $type);
	}
}
