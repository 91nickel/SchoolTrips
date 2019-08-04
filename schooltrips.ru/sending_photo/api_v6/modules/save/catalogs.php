<?php
/**
 * Редактирование каталогов
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class CatalogsSave extends EntitySave
{
    public function __construct($entity, \Amo\CRM $instance)
    {
		parent::__construct($entity, $instance);
	}
	
}
