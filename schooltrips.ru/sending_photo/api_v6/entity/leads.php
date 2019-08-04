<?php
/**
 * Класс сделок
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class Leads extends Entity
{
    /**
     * Название сущности
     */
    protected $name;
    /**
     * Бюджет сделки
     */
    protected $price;
    /**
     * Теги
     */
    protected $tags;
    /**
     * Статус
     */
    protected $status_id;
    /**
     * Воронка
     */
    protected $pipeline_id;
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
    /**
     * Прикрепленные контакты - ID
     */
    protected $contact_links = null;
    /**
     * Прикрепленные элементы каталогов
     */
    protected $catalog_element_links = null;
	
    protected $_contacts,
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
     * Получение имени сделки
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Получение ID комнаии сделки
     */
    public function companyId()
    {
        return $this->linked_company_id;
    }

    /**
     * Получение бюджета сделки
     */
    public function price()
    {
        return (int)$this->price;
    }

    /**
     * Получение метки времени закрытия
     */
    public function closedTime()
    {
        return $this->date_close;
    }

    /**
     * Получение даты закрытия
     */
    public function closedDate($format = 'Y-m-d H:i:s')
    {
        if (empty($this->date_close)) {
            $this->date_close = 1;
        }
        return Fn::monthRu(date($format, $this->date_close));
    }

    /**
     * Получение объекта статуса
     */
    public function status()
    {
		$leadinfo = new Leadinfo($this->_instance);
        if ($status = $leadinfo->statusFromId($this->status_id, $this->pipeline_id)) {

            return $status;
        }
        return null;
    }

    /**
     * Получение ID статуса
     */
    public function statusId()
    {
        return (int)$this->status_id;
    }

    /**
     * Получение объекта воронки
     */
    public function pipeline()
    {
		$leadinfo = new Leadinfo($this->_instance);
        if ($pipeline = $leadinfo->pipelineFromId($this->pipeline_id)) {
            return $pipeline;
        }
        return null;
    }

    /**
     * Получение ID воронки
     */
    public function pipelineId()
    {
        return (int)$this->pipeline_id;
    }
	
    /**
     * Получение объекта следующего статуса
     */
    public function statusNext()
    {
		$leadinfo = new Leadinfo($this->_instance);
        return $leadinfo->statusNextFrom($this->pipeline_id, $this->status_id);
    }

    /**
     * Удалена ли сделка
     */
    public function isDeleted()
    {
        return (bool)$this->deleted;
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
     * Список ID связанных контактов
     */
    public function contactLinks()
    {
		if (is_null($this->contact_links)) {
			$this->contact_links = $this->_instance->contacts->links()->from( $this->id );
		}
		return $this->contact_links;
	}
	
    /**
     * Список связанных контактов
     */
    public function contacts()
    {
		if (is_null($this->_contacts)) {
			$this->_loadContacts();
		}
		return $this->_contacts;
	}
	
    /**
     * Получение связанных контактов
     */
    private function _loadContacts()
    {
		if (!$links = $this->contactLinks()) {
			$this->_contacts = array();
		}
		if (!$contacts = $this->_instance->contacts->get()->byId($links)) {
			$this->_contacts = array();
		}
		$this->_contacts = $contacts;
	}
	
    /**
     * Основной контакт сделки
     */
    public function contact()
    {
		$contacts = $this->contacts();
		if (!isset($contacts[0])) {
			return null;
		}
		return $contacts[0];
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
     * Список связанных открытых задач
     */
    public function openTasks()
    {
		$open_tasks = [];
		$tasks = $this->tasks();
		foreach ($tasks as $task) {
			if ($task->raw()->status == 0) {
				$open_tasks[]= $task;
			}
		}
		return $open_tasks;
	}
	
	
    /**
     * Получение связанных задач
     */
    private function _loadTasks()
    {
		if (!$tasks = $this->_instance->tasks->get()->byLeadId($this->id)) {
			$this->_tasks = [];
		}
		$this->_tasks = $tasks;
	}
	
    /**
     * Список ID связанных элементов списка
	 * @param $catalog_id - ID списка
     */
    public function catalogElementLinks($catalog_id)
    {
		$linked = [
			'ids' => [],
			'quantity' => []
		];
		$service = $this->_instance->links->lists();
		$service->from($this->id, 'leads')->to($catalog_id);
		$result = $service->run();
		foreach ($result->links as $link) {
			$linked['ids'][]= $link->to_id;
			$linked['quantity'][$link->to_id]= $link->quantity;
		}
		return $linked;
	}
	
    /**
     * Список связанных элементов списка
	 * @param $catalog_id - ID списка
     */
    public function catalogElements($catalog_id)
    {
		$linked = $this->catalogElementLinks($catalog_id);
		if (empty($linked)) {
			return [];
		}
		if (!$entitys = $this->_instance->catalogElements->get()->byIds($linked['ids'])) {
			return [];
		}
		foreach ($entitys as $entity) {
			if (isset($linked['quantity'][$entity->raw()->id])) {
				$entity->quantity = $linked['quantity'][$entity->raw()->id];
			}
		}
		return $entitys;
	}
	
    /**
     * Связка элементов списка со сделкой
	 * @param $element_id - ID элемента списка
	 * @param $catalog_id - ID списка
	 * @param $quantity - количество
     */
    public function linkCatalogElement($element_id, $catalog_id, $quantity = 1)
    {
		$service = $this->_instance->links()->set();
		$service->link([
			'from' => 'leads',
			'from_id' => $this->raw()->id,
			'to' => 'catalog_elements',
			'to_id' => $element_id,
			'to_catalog_id' => $catalog_id,
			'quantity' => $quantity,
		]);
		return $service->run();
	}
	
    /**
     * Убрать связь элементов списка со сделкой
	 * @param $element_id - ID элемента списка
	 * @param $catalog_id - ID списка
     */
    public function unlinkCatalogElement($element_id, $catalog_id)
    {
		$service = $this->_instance->links()->set();
		$service->unlink([
			'from' => 'leads',
			'from_id' => $this->raw()->id,
			'to' => 'catalog_elements',
			'to_id' => $element_id,
			'to_catalog_id' => $catalog_id,
		]);
		return $service->run();
	}
	
    /**
     * Получение связанной компании
     */
    private function _loadCompany()
    {
		$this->_company = $this->_instance->company->get()->byId($this->raw()->linked_company_id);
	}
	
    /**
     * Добавим задачу к сделке
     */
    public function addTask($type = 'Follow-up')
    {
		return $this->_instance->tasks->set()->elemType('lead')->elemId($this->id)->type($type)->respId($this->responsible_user_id);
	}
	
    /**
     * Добавим примечание к сделке
     */
    public function addNote($type = 4)
    {
		return $this->_instance->notes->set()->elemType('lead')->elemId($this->id)->type($type)->respId($this->responsible_user_id);
	}
}
