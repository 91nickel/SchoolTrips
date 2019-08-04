<?php
/**
 * Удаление каталогов
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class CatalogsDelete extends EntityDelete
{
    public function __construct($entity, \Amo\CRM $instance)
    {
		parent::__construct($entity, $instance);
	}
	
}
