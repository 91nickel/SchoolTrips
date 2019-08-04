<?php
/**
 * Получение покупателей
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class Customers_periodsGet extends EntityList
{
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct($instance);
		$this->entity('customers_periods');
	}
	
    /**
     * Поиск по ID
     */
    public function id($val)
    {
		$this->setMax(1);
		return $this->param('id', $val);
	}
	
    /**
     * Поиск по количеству дней в периоде
     */
    public function period($val)
    {
		return $this->param('period', $val);
	}
	
    /**
     * Порядковый номер периода при отображении
     */
    public function sort($val)
    {
		return $this->param('sort', $val);
	}
	
    /**
     * Поиск по цвету (#fffeb2|#87f2c0)
     */
    public function color($val)
    {
		return $this->param('color', $val);
	}
	
    /**
     * Поиск по типу периода (after|before)
     */
    public function type($val)
    {
		return $this->param('type', $val);
	}
	
    /**
     * Поиск по кол-ву дней до покупки
     */
    public function beforeBuy($val)
    {
		return $this->param('before_buy', $val);
	}
	
    /**
     * Поиск по кол-ву дней после покупки
     */
    public function afterBuy($val)
    {
		return $this->param('after_buy', $val);
	}
	
    /**
     * Получение данных
     */
    public function run()
    {
		if ($values = parent::run()) {
			
			$periods = array();
			foreach ($values as $arr) {
				
				foreach ($arr as $value) {
					
					$periods[]= new Customers_periodsEntity($value, $this->_instance);
				}
			}
			return $periods;
		}
		return false;
	}
}
