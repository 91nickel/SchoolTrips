<?php
/**
 * Класс сущности - Период покупателя
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class Customers_periodsEntity extends EntityEditable
{
    public function __construct($raw, \Amo\CRM $instance)
    {
		parent::__construct('customers_periods', $raw, $instance);
	}
}
