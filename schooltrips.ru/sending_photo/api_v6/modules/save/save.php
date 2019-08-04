<?php
/**
 * Редактирование сущности
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class EntitySave
{
    protected $_fields = array(),
			  $_instance,
			  $entity;
	
    public function __construct($entity, \Amo\CRM $instance)
    {
		$this->_instance = $instance;
		$this->entity = $entity;
	}
	
    /**
     * Update entitys
	 * @return 
     */
    public function update($entitys_vals)
    {
		$entitys = [];
		foreach ($entitys_vals as $entitys_val) {
			$entitys[$entitys_val->raw()->id] = $entitys_val;
		}
        $p = 0;
        $i = 0;
        $parts = array();
        $results = array();

        foreach ($entitys as $k=>$entity) {
            $parts[$p][] = $entity->raw();
            if ($i == 300) {
                $i = 0;
                $p++;
            } else {
                $i++;
            }
        }
        foreach ($parts as $part) {
            if (!empty($part)) {
				$result = $this->_update($part);
				foreach ($result->{$this->entity}->update->{$this->entity} as $raw) {
					$entitys[$raw->request_id]->raw($raw);
					$entitys[$raw->request_id]->saveStatus(true);
				}
				foreach ($result->{$this->entity}->update->errors as $error) {
					$entitys[$error->id]->saveStatus($error);
				}
            }
        }
        return $entitys;
	}
	
    /**
     * Update entitys
	 * @return 
     */
    protected function _update($entitys)
    {
		$data = [];
		$data['request'][$this->entity]['update'] = $entitys;
		$result = $this->_instance->query('post', '/private/api/v2/json/'.$this->entity.'/set', $data);
		return $result->getResp();
	}
}
