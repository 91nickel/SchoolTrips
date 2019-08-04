<?php
/**
 * Класс сущности - Покупка
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class TransactionsEntity extends Entity
{
    public function __construct($ename, $entity, \Amo\CRM $instance)
    {
        parent::__construct($ename, $entity, $instance);
	}
	
}
