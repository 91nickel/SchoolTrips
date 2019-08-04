<?php
/**
 * Класс компаний
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class Company extends Entity
{
    /**
     * Название сущности
     */
    protected $name;
    /**
     * Теги
     */
    protected $tags;
    /**
     * Привязанные сделки
     */
    protected $linked_leads_id;
    /**
     * Дополнительные поля
     */
    protected $custom_fields;
    /**
     * Доп.поля по ID
     */
    protected $custom_from_id = array();
    /**
     * Доп.поля по имени
     */
    protected $custom_from_name = array();

    protected $_leads,
			  $_tasks;

    //=======================================================

    public function __construct($ename, $entity, \Amo\CRM $instance)
    {
        parent::__construct($ename, $entity, $instance);

        if (!isset($this->custom_fields)) {

            $this->custom_fields = array();
        }
        foreach ($this->custom_fields as $cfield) {

            if (!isset($this->custom_from_id[$cfield->id])) {

                $this->custom_from_id[$cfield->id] = (object)array(
														'id' => $cfield->id, 
														'values' => array()
													);
                $this->custom_from_name[$cfield->name] = $cfield->id;
            }
			foreach ($cfield->values as &$cf_value) {
				if (!is_object($cf_value)) {
					$cf_value = (object)array(
						'value' => $cf_value,
					);
				}
			}
            $this->custom_from_id[$cfield->id]->values = $cfield->values;
        }
        $this->custom_from_id[0] = (object)array(
			'id' => 0,
            'values' => array(
				(object)array(
					'value' => '', 
					'enum' => 0
				)
			)
        );
    }

    /**
     * Получение имени компании
     */
    public function name()
    {
        return $this->name;
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
     * Адресное доп.поле
     */
    public function customAddress($cf_name)
    {
		$subtypes = ['', 'other1', 'other2', 'city', 'region', 'index', 'counrty'];
		$data = (object)[
			'counrty' => '',
			'region' => '',
			'city' => '',
			'index' => '',
			'other1' => '',
			'other2' => '',
		];
		$cf = $this->customByName($cf_name, false);
		foreach ($cf->values as $val) {
			$data->{$subtypes[$val->subtype]} = $val->value;
		}
		return $data;
	}

    /**
     * Список имен доп.полей
     */
    public function customNames()
    {
        if (!empty($this->custom_from_name)) {
            return array_keys($this->custom_from_name);
        }
        return array();
    }

    /**
     * Список ID связанных сделок
     */
    public function leadLinks()
    {
		return $this->linked_leads_id;
	}
	
    /**
     * Список связанных сделок
     */
    public function leads()
    {
		if (is_null($this->_leads)) {
			$this->_getLeads();
		}
		return $this->_leads;
	}
	
    /**
     * Загрузка связанных сделок
     */
    private function _getLeads()
    {
		if (!$leads = $this->_instance->leads->get()->byId($this->leadLinks())) {
			$this->_leads = array();
		}
		$this->_leads = $leads;
	}
	
    /**
     * Сделка компании
     */
    public function lead()
    {
		$leads = $this->leads();
		if (!isset($leads[0])) {
			return null;
		}
		return $leads[0];
	}
	
    /**
     * Связанные контакты
     */
    public function contacts()
    {
		return [];
	}
	
    /**
     * Список связанных задач
     */
    public function tasks()
    {
		if (is_null($this->_tasks)) {
			$this->_loadTasks();
		}
		return $this->_tasks;
	}
	
    /**
     * Получение связанных задач
     */
    private function _loadTasks()
    {
		if (!$tasks = $this->_instance->tasks->get()->byCompanyId($this->id)) {
			$this->_tasks = [];
		}
		$this->_tasks = $tasks;
	}
	
    /**
     * Добавим задачу к компании
     */
    public function addTask($type = 'Follow-up')
    {
		return $this->_instance->tasks->set()->elemType('company')->elemId($this->id)->type($type)->respId($this->responsible_user_id);
	}
	
    /**
     * Добавим примечание к компании
     */
    public function addNote($type = 4)
    {
		return $this->_instance->notes->set()->elemType('company')->elemId($this->id)->type($type)->respId($this->responsible_user_id);
	}
	
	
    /**
     * Добавим звонок к компании
     */
    public function addCall($type = 10, $call_data)
    {
		return $this->addNote($type)->text(json_encode($call_data));
	}
	
    /**
     * Добавим контакта к компании
     */
    public function addContact()
    {
		return $this->_instance->contacts->set()->company($this->id)->respId($this->responsible_user_id);
	}
}
