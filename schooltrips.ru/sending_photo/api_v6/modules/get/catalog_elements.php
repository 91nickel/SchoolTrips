<?php
/**
 * Получение элементов каталогов
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class Catalog_elementsGet extends EntityList
{
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct($instance);
		$this->entity('catalog_elements');
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
     * Поиск по ID
     */
    public function byIds($values)
    {
		$this->setMax(count($values));
		$this->param('id', $values);
		return $this->run();
	}
	
    /**
     * Поиск по ID каталога
     */
    public function catalogId($val)
    {
		return $this->param('catalog_id', $val);
	}
	
    /**
     * Поиск по запросу
     */
    public function term($val)
    {
		return $this->param('term', $val);
	}
	
    /**
     * Сортировка по полю
     */
    public function orderBy($val)
    {
		return $this->param('order_by', $val);
	}
	
    /**
     * Тип сортировки (ASC|DESC)
     */
    public function orderType($val)
    {
		return $this->param('order_type', $val);
	}
	
    /**
     * Поиск по странице выборки
     */
    public function page($val)
    {
		return $this->param('PAGEN_1', $val);
	}
	
    /**
     * Получение данных
     */
    public function run()
    {
		$values = parent::run();
		if (is_array($values)) {
			$elements = array();
			foreach ($values as $value) {
				
				$elements[]= new Catalog_elementsEntity($value, $value->catalog_id, $this->_instance);
			}
			return $elements;
		}
		return false;
	}
}
