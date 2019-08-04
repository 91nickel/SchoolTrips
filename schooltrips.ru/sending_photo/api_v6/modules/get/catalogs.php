<?php
/**
 * Получение каталогов
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class CatalogsGet extends EntityList
{
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct($instance);
		$this->entity('catalogs');
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
     * Поиск по названию
     */
    public function name($val)
    {
		return $this->param('name', $val);
	}
	
    /**
     * Поиск по ID создателя
     */
    public function createdBy($val)
    {
		return $this->param('created_by', $val);
	}
	
    /**
     * Поиск по дате создания
     */
    public function createdDate($val)
    {
		return $this->param('date_create', $val);
	}
	
    /**
     * Получение данных
     */
    public function run()
    {
		if ($values = parent::run()) {
			
			$catalogs = array();
			foreach ($values as $value) {
				
				$catalogs[]= new CatalogsEntity($value, $this->_instance);
			}
			return $catalogs;
		}
		return false;
	}
}
