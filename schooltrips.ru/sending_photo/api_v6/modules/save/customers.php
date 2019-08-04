<?php
/**
 * Редактирование покупателей
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class CustomersSave extends EntitySave
{
    public function __construct($entity, \Amo\CRM $instance)
    {
		parent::__construct($entity, $instance);
	}
	
}
