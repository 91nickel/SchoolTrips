<?php
/**
 * Класс сущности
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class EntityEditable
{
    protected $_instance,
			  $edithor,
			  $entity,
			  $saved,
			  $raw;
	
    public function __construct($entity, $raw, \Amo\CRM $instance)
    {
		$this->_instance = $instance;
		$this->entity = $entity;
		$this->raw = $raw;
		$this->edithor = new EntityEdithor($this, $instance);
	}
	
    /**
     * Get/Set RAW data
	 * @return object
     */
    public function raw($data = array())
    {
		if (!empty($data)) {
			$this->raw = $data;
		}
        return $this->raw;
    }
	
    /**
     * Set entity data
	 * @return EntityEdithor
     */
    public function edit()
    {
        return $this->edithor;
    }
	
    /**
     * Get created user
	 * @return object
     */
    public function createdUser()
    {
        return $this->_instance->users()->byId(
			$this->raw->created_by
		);
    }
	
    /**
     * Get modified user
	 * @return object
     */
    public function modifiedUser()
    {
        return $this->_instance->users()->byId(
			$this->raw->modified_by
		);
    }
	
    /**
     * Get main user
	 * @return object
     */
    public function mainUser()
    {
        return $this->_instance->users()->byId(
			$this->raw->main_user_id
		);
    }
	
    /**
     * Get created date
	 * @param string $format Date format
	 * @param integer $type Date type (0,1)
	 * @return string
     */
	public function createdDate($format = 'j F, H:i', $type = 0)
	{
		return monthRU(date($format, $this->raw->date_create), $type);
	}
	
    /**
     * Get modified date
	 * @param string $format Date format
	 * @param integer $type Date type (0,1)
	 * @return string
     */
	public function modifyDate($format = 'j F, H:i', $type = 0)
	{
		return monthRU(date($format, $this->raw->date_modify), $type);
	}
	
    /**
     * Write RAW data
	 * @param $key string field name
	 * @param $val mixed field val
	 * @return EntityEditable
     */
    public function field($key, $val)
    {
		$this->raw->$key = $val;
		return $this;
	}
	
    /**
     * Save entity
     */
    public function save()
    {
		$this->saved = null;
		if (is_numeric($this->raw()->date_modify)) {
			$this->field('date_modify', time());
		}
		if (!is_numeric($this->raw()->id)) {
			$response = $this->_instance->{$this->entity}()->create([$this]);
			if (is_numeric($response[0]->raw()->id)) {
				return true;
			}
			return $response;
		}
		$this->_instance->{$this->entity}()->save([$this]);
		return $this->saved;
	}
	
    /**
     * Get/Set save status
	 * @param $val mixed field val
	 * @return mixed status
     */
    public function saveStatus($val = null)
    {
		if (!is_null($val)) {
			$this->saved = $val;
		}
		return $this->saved;
	}
	
    /**
     * Delete entity
     */
    public function delete()
    {
		$response = $this->_instance->{$this->entity}()->delete([$this]);
		return $response;
	}
}
