<?php
/**
 * Класс контактов
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class Contacts extends Entity
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
			  $_company,
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
     * Получение имени контакта
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Получение ID комнаии контакта
     */
    public function companyId()
    {
        return $this->linked_company_id;
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
     * Список ID связанных покупателей
     */
    public function customerLinks()
    {
		$links = [];
		$result = $this->_instance->links->lists()->from($this->raw()->id, 'contacts')->to(null, 'customers')->run();
		if (!empty($result->links)) {
			foreach($result->links as $link) {
				$links[]= $link->to_id;
			}
		}
		return $links;
	}
	
    /**
     * Список связанных покупателей
     */
    public function customers()
    {
		$links = $this->customerLinks();
		if (!empty($links)) {
			return $this->_instance->customers->get()->fromId($links);
		}
		return [];
	}

    /**
     * Список ID связанных сделок
     */
    public function leadLinks()
    {
		return $this->raw()->linked_leads_id;
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
     * Список открытых сделок
     */
    public function openLeads()
    {
		$open_leads = [];
		$leads = $this->leads();
		foreach ($leads as $lead) {
			if (!in_array($lead->raw()->status_id, [142, 143])) {
				$open_leads[]= $lead;
			}
		}
		return $open_leads;
	}
	
    /**
     * Открытая сделка
     */
    public function openLead()
    {
		$open_leads = $this->openLeads();
		if (!isset($open_leads[0])) {
			return null;
		}
		return $open_leads[0];
	}
	
    /**
     * Загрузка связанных сделок
     */
    private function _getLeads()
    {
		if (!$leads = $this->_instance->leads->get()->byId($this->leadLinks())) {
			$this->_leads = array();
		} else {
			$this->_leads = $leads;
		}
	}
	
    /**
     * Первая сделка контакта
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
     * Последняя сделка контакта
     */
    public function endLead()
    {
		$leads = $this->leads();
		if (!isset($leads[0])) {
			return null;
		}
		return end($leads);
	}
	
    /**
     * Связанная компания
     */
    public function company()
    {
		if (is_null($this->_company)) {
			$this->_loadCompany();
		}
		return $this->_company;
	}
	
    /**
     * Получение связанной компании
     */
    private function _loadCompany()
    {
		if ($this->hasCompany()) {
			$this->_company = $this->_instance->company->get()->byId($this->raw()->linked_company_id);
		}
		return $this->_company;
	}
	
    /**
     * Есть ли компания
     */
    public function hasCompany()
    {
		return (bool)$this->raw()->linked_company_id;
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
		if (!$tasks = $this->_instance->tasks->get()->byContactId($this->id)) {
			$this->_tasks = [];
		}
		$this->_tasks = $tasks;
	}
	
    /**
     * Добавим задачу к контакту
     */
    public function addTask($type = 'Follow-up')
    {
		return $this->_instance->tasks->set()->elemType($this->raw()->type)->elemId($this->id)->type($type)->respId($this->responsible_user_id);
	}
	
    /**
     * Добавим примечание к контакту
     */
    public function addNote($type = 4)
    {
		return $this->_instance->notes->set()->elemType($this->raw()->type)->elemId($this->id)->type($type)->respId($this->responsible_user_id);
	}
	
    /**
     * Добавим сделку к контакту
     */
    public function addLead()
    {
		return $this->_instance->leads->set()->fromContact($this)->respId($this->responsible_user_id);
	}
	
    /**
     * Добавим звонок к контакту
     */
    public function addCall($type = 10, $call_data)
    {
		return $this->addNote($type)->text(json_encode($call_data));
	}
	
    /**
     * Присвоим сделки
     */
    public function setLeads($leads)
    {
		if (!is_array($leads)) {
			return false;
		}
		$raw_leads = $this->raw()->linked_leads_id;
		foreach ($leads as $lead) {
			
			if (!in_array($lead->id(), $raw_leads)) {
				$raw_leads[]= $lead->id();
			}
		}
		$this->raw->linked_leads_id = $raw_leads;
		return $raw_leads;
	}
}
