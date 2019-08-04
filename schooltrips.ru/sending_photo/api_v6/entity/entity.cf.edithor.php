<?php
/**
 * Класс редактирования сущности
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class EntityCfEdithor extends EntityEdithor
{
	private $entity_cfields = [],
			$entity_cfields_from_name = [];
	
    public function __construct($entity, $cf_entity, \Amo\CRM $instance)
    {
		parent::__construct($entity, $instance);
		$this->cfinit($cf_entity);
	}
	
    /**
     * Write CF RAW data
	 * @param $cf_id integer custom field id
	 * @param $val mixed field val
	 * @return EntityCfEdithor
     */
    public function cfinit($cf_entity)
    {
		$this->entity_cfields = [];
		$this->entity_cfields_from_name = [];
		
		$this->entity_cfields = $this->_instance->cfields()->fromEntity($cf_entity);
		foreach ($this->entity_cfields as $cfield) {
			
			$this->entity_cfields_from_name[$cfield->name] = $cfield->id;
		}
		return $this;
	}
	
    /**
     * Write CF RAW data
	 * @param $cf_id integer custom field id
	 * @param $val mixed field val
	 * @return EntityCfEdithor
     */
    public function cfieldById($cf_id, $val)
    {
		if (!isset($this->entity_cfields[$cf_id])) {
			return $this;
		}
		$cfield = $this->entity_cfields[$cf_id];
		$cf_method = '_cfield_type_'.$cfield->type_id;
		if (!method_exists($this, $cf_method)) {
			$cf_method = '_cfield_type_default';
		}
		$data = $this->$cf_method($cfield, $val);
		$this->entity->cfield($cf_id, $data);
		return $this;
	}

    /**
     * Write CF RAW data
	 * @param $cf_name string custom field name
	 * @param $val mixed field val
	 * @return EntityCfEdithor
     */
    public function cfield($cf_name, $val)
    {
		if (!isset($this->entity_cfields_from_name[$cf_name])) {
			return $this;
		}
		return $this->cfieldById($this->entity_cfields_from_name[$cf_name], $val);
	}
	
    /**
     * CF data default type
	 * @param $cfield object custom field
	 * @param $val mixed field val
	 * @return object
     */
    protected function _cfield_type_default($cfield, $val)
    {
		return (object)[
			'id' => $cfield->id,
			'name' => $cfield->name,
			'code' => $cfield->code,
			'values' => [
				(object)['value' => $val]
			]
		];
	}
	
    /**
     * CF data by type - radio
	 * @param $cfield object custom field
	 * @param $val mixed field val
	 * @return object
     */
    protected function _cfield_type_10($cfield, $val)
    {
		return $this->_cfield_type_4($cfield, $val);
	}
	
    /**
     * CF data by type - select
	 * @param $cfield object custom field
	 * @param $val mixed field val
	 * @return object
     */
    protected function _cfield_type_4($cfield, $val)
    {
		$data = (object)[
			'id' => $cfield->id,
			'name' => $cfield->name,
			'code' => $cfield->code,
			'values' => []
		];
		$enums = (array)$cfield->enums;
		if (in_array($val, $enums)) {
			$enum = array_search($val, $enums);
			$data->values[]= (object)[
				'value' => $val,
				'enum' => $enum
			];
		}
		return $data;
	}
	
    /**
     * CF data by type - multi-select
	 * @param $cfield object custom field
	 * @param $values mixed field values
	 * @return object
     */
    protected function _cfield_type_5($cfield, $values)
    {
		if (!is_array($values)) {
			$values = [$values];
		}
		$data = (object)[
			'id' => $cfield->id,
			'name' => $cfield->name,
			'code' => $cfield->code,
			'values' => []
		];
		$enums = (array)$cfield->enums;
		foreach ($values as $val) {
			if (in_array($val, $enums)) {
				$enum = array_search($val, $enums);
				$data->values[]= (object)[
					'value' => $val,
					'enum' => $enum
				];
			}
		}
		return $data;
	}
}
