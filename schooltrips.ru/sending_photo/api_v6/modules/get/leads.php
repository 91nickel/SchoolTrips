<?php
/**
 * Получение сделок
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class LeadsGet extends EntityGet
{
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct($instance);
		$this->setKey('leads');
		$this->setEntity('leads');
	}
	
    /**
     * Поиск сделок
     */
    public function search($query, $name = false, $max = 0)
    {
        if ($max > 0) {
            $this->setParam('limit_rows', $max);
        }
        if ($name) {
            $query = $this->format($query, $name);
			if (empty($query)) {
				throw new \Exception('Invalid search query from cfield "'.$name.'": '.$query);
			}
        }
        $this->setParam('query', $query);
        $result = $this->run();

        if (!empty($result)) {
            if (!$name) {
                return $result;
            }
            foreach ($result as $lead) {

                $customs = $lead->customByName($name, false);
                if (empty($customs->values)) {
                    continue;
                }
                foreach ($customs->values as $cf) {
                    if ($this->format($cf->value, $name) == $query) return $lead;
                }
            }
        }
        return null;
    }
	
    /**
     * Форматирование поискового запроса
     */
    public function format($query, $name = false)
    {
        if ($name == 'Телефон' && strlen($query) >= 10) {
            $query = substr( preg_replace('#[^0-9]+#Uis', '', $query ), -10);
        }
        return $query;
    }
	
    /**
     * Объект entity
     */
    public function entityObject($entity_data)
    {
		return $this->getEntityObject('leads', $entity_data, $this->_instance);
	}
}