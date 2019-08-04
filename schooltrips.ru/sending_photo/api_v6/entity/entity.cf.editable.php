<?php
/**
 * Класс сущности с доп.полями
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class EntityCfEditable extends EntityEditable
{
    protected $cf_entity,
			  $custom_from_id = [],
			  $custom_from_name = [],
			  $raw_cfields = [];
	
    public function __construct($entity, $raw, $cf_entity, \Amo\CRM $instance)
    {
		parent::__construct($entity, $raw, $instance);
		$this->cfentity($cf_entity);
		$this->cfinit($raw);
	}
	
    /**
     * Set CF entity key
     */
    public function cfentity($cf_key)
    {
		$this->cf_entity = $cf_key;
		$this->edithor = new EntityCfEdithor($this, $this->cf_entity, $this->_instance);
	}
	
    /**
     * Init CF entity
     */
    public function cfinit($raw)
    {
		if (!isset($raw->custom_fields)) {
			
			$raw->custom_fields = [];
		}
        foreach ($raw->custom_fields as $cfield) {

			if (!isset($cfield->code)) {
				$cfield->code = '';
			}
            if (!isset($this->custom_from_id[$cfield->id])) {

                $this->custom_from_id[$cfield->id] = (object)[
														'id' => $cfield->id, 
														'name' => $cfield->name,
														'code' => $cfield->code,
														'values' => []
													];
                $this->custom_from_name[$cfield->name] = $cfield->id;
            }
			foreach ($cfield->values as &$cf_value) {
				if (!is_object($cf_value)) {
					$cf_value = (object)[
						'value' => $cf_value,
					];
				}
			}
            $this->custom_from_id[$cfield->id]->values = $cfield->values;
			$this->raw_cfields[$cfield->id] = $cfield;
        }
        $this->custom_from_id[0] = (object)[
			'id' => 0,
			'name' => '',
			'code' => '',
            'values' => [
			(object)[
					'value' => '', 
					'enum' => 0
				]
			]
        ];
	}
	
    /**
     * Get/Set RAW data
	 * @return object
     */
    public function raw($data = array())
    {
		if (!empty($data)) {
			$this->raw = $data;
			$this->cfinit($this->raw);
			$this->raw->custom_fields = array_values($this->raw_cfields);
		}
        return $this->raw;
    }
	
    /**
     * Get CF data
	 * @return array
     */
    public function cf()
    {
		return $this->raw_cfields;
	}
	
    /**
     * Обращение к дополнительному полю по имени поля
     */
    public function customByName($name, $once = true)
    {
        if (!isset($this->custom_from_name[$name])) {
            return $this->customById(0, $once);
        }
        return $this->customById($this->custom_from_name[$name], $once);
    }

    /**
     * /**
     * Обращение к дополнительному полю по ID поля
     */
    public function customById($cf_id, $once = true)
    {
        if (empty($this->custom_from_id[$cf_id])) {
            return null;
        }
        if ($once) {
            return $this->custom_from_id[$cf_id]->values[0];
        }
        return $this->custom_from_id[$cf_id];
    }

    /**
     * Список имен доп.полей
     */
    public function customNames()
    {
        if (!empty($this->custom_from_name)) {
            return array_keys($this->custom_from_name);
        }
        return [];
    }
	
    /**
     * Write CF RAW data
	 * @param $key integer cf id
	 * @param $val object cf val
	 * @return EntityEditable
     */
    public function cfield($id, $val)
    {
		$this->raw_cfields[$id] = $val;
		return $this;
	}
}
