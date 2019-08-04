<?php
/**
 * Класс сущности - Элементы каталогов
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class Catalog_elementsEntity extends EntityCfEditable
{
	private 
		$_catalog;
	public 
		$quantity = 0;
	
    public function __construct($raw, $cf_entity, \Amo\CRM $instance)
    {
		parent::__construct('catalog_elements', $raw, $cf_entity, $instance);
	}
	
    /**
     * Set CF entity key
     */
    public function cfentity($cf_key)
    {
		$this->cf_entity = $cf_key;
		$this->edithor = new Catalog_elementsEntityCfEdithor($this, $this->cf_entity, $this->_instance);
	}
	
    /**
     * Get catalog
     */
    public function catalog()
    {
		if (is_null($this->_catalog)) {
			$catalogs = $this->_instance->catalogs()->get()->id($this->raw()->catalog_id)->run();
			if (isset($catalogs[0])) {
				$this->_catalog = $catalogs[0];
			}
		}
        return $this->_catalog;
    }
}
