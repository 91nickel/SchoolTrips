<?php
/**
 * Удаление покупателей
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class CustomersDelete extends EntityDelete
{
    public function __construct($entity, \Amo\CRM $instance)
    {
		parent::__construct($entity, $instance);
	}
	
}
