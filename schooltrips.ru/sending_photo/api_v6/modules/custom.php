<?php
/**
 * amoCRM класс
 * Получение информации о доп.полях
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 * @version 1.00
 */
namespace Amo;

class Custom
{
    private $_instance,
			$from_id = array(
				'contacts' => array(), 'leads' => array(), 'companies' => array(), 
			),
			$from_name = array(
				'contacts' => array(), 'leads' => array(), 'companies' => array(), 
			);

    public function __construct(\Amo\CRM $instance)
	{
		$this->_instance = $instance;
		$current = $this->_instance->getCurrent();
		
        foreach ($current->custom_fields as $entity=>$custom_array) {

            foreach ($custom_array as $cfield) {

                $this->from_id[$entity][$cfield->id] = $cfield;
                $this->from_name[$entity][$cfield->name] = $cfield->id;
            }
        }
    }
	
    /**
     * Получение полей сущности
	 * @param string $entity contacts|leads|companies
     */
    public function customFrom($entity)
    {
        return $this->from_id[$entity];
    }

    /**
     * Получение массива идентификаторов полей сущности
	 * @param string $entity contacts|leads|companies
     */
    public function customIdsFrom($entity)
    {
        return array_keys($this->from_id[$entity]);
    }

    /**
     * Получение массива названий полей сущности
	 * @param string $entity contacts|leads|companies
     */
    public function customNamesFrom($entity)
    {
        return array_keys($this->from_name[$entity]);
    }

    /**
     * Получение информации о поле сущности по имени поля
     */
    public function customFromName($cf_name, $entity)
    {
        return $this->customFromId($this->from_name[$entity][$cf_name], $entity);
    }

    /**
     * Получение информации о поле сущности по ID поля
     */
    public function customFromId($cf_id, $entity)
    {
        return $this->from_id[$entity][$cf_id];
    }
}
