<?php
/**
 * Редактирование элементов каталогов
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class Catalog_elementsSave extends EntitySave
{
    public function __construct($entity, \Amo\CRM $instance)
    {
		parent::__construct($entity, $instance);
	}
	
}