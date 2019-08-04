<?php
/**
 * Добавление сделок
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class LeadsSet extends EntitySet
{
	private $from_entity;
	
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct('leads', $instance);
	}
	
    /**
     * Добавление через контакт
     */
    public function fromContact(\Amo\Contacts $contact)
    {
		$this->from_entity = $contact;
		return $this;
	}
	
    /**
     * Добавленные сущности
     */
    public function addedLeads()
    {
		$leads = [];
		$entity_class = 'Amo\\Leads';
		
		foreach ($this->request as $lead_data) {
			$leads[]= new $entity_class('leads', $lead_data, $this->_instance);
		}
		return $leads;
	}
	
    /**
     * Добавленная сущность
     */
    public function addedLead()
    {
		$leads = $this->addedLeads();
		if (!isset($leads[0])) {
			return null;
		}
		return end($leads);
	}
	
    /**
     * Название сделки
     */
    public function name($value)
    {
        if (!empty($value)) {
			$this->setValue('name', $value);
		}
        return $this;
    }

    /**
     * Ключевые слова
     */
    public function tags($value)
    {
        if (!empty($value)) {
			$this->setValue('tags', $value);
		}
        return $this;
    }

    /**
     * ID статуса сделки
     */
    public function statusId($value)
    {
        if (!empty($value)) {
			$this->setValue('status_id', $value);
		}
        return $this;
    }
	
    /**
     * ID воронки сделки
     */
    public function pipelineId($value)
    {
        if (!empty($value)) {
			$this->setValue('pipeline_id', $value);
		}
        return $this;
    }

    /**
     * Статус сделки
     */
    public function status($status_name, $pipeline_name = null)
    {
		if (!is_null($pipeline_name)) {
			
			if (!isset($this->lead_pipeline[$pipeline_name])) {
				throw new \Exception('Incorrect lead pipeline name: '.(string)$pipeline_name);
			}
			$this->setValue('pipeline_id', $this->lead_pipeline[$pipeline_name]['id']);
			
			if (!isset($this->lead_pipeline[$pipeline_name]['status'][$status_name])) {
				throw new \Exception('Incorrect lead status name: '.(string)$status_name.', from pipeline: '.$pipeline_name);
			}
			return $this->setValue('status_id', $this->lead_pipeline[$pipeline_name]['status'][$status_name]);
		}
		if (!isset($this->lead_status[$status_name])) {
			throw new \Exception('Incorrect lead status name: '.(string)$status_name);
		}
		return $this->setValue('status_id', $this->lead_status[$status_name]);
    }

    /**
     * Бюджет сделки
     */
    public function price($value)
    {
        $this->setValue('price', (int)$value);
        return $this;
    }

    /**
     * Установка временных custom данных для отправки
     */
    public function custom($name, $values, $replace = false, $multi = false)
    {
		if (empty($values) && !$replace) {
			return $this;
		}
        if (!is_array($values)) {
            $values = array($values);
        }
		return $this->setCustom($name, $values, $replace, $multi);
    }

    /**
     * Ответственный
     */
    public function respId($value)
    {
        if (!empty($value)) {
			$this->setValue('responsible_user_id', $value);
		}
        return $this;
    }
	
    /**
     * Добавление
     */
    public function run($request = false)
    {
		$result = parent::run();
		$added_leads = $this->addedLeads();
		
		if (!is_null($this->from_entity)) {
	
			if (empty($added_leads)) {
				throw new \Exception('Error link created leads to contact: no created leads');
			}
			$client = $this->from_entity;
			if ($client->raw()->type == 'contact') {
				$service = $this->_instance->contacts()->update();
			}
			if ($client->raw()->type == 'company') {
				$service = $this->_instance->company()->update();
			}
			$client->setLeads($added_leads);
			$service->set($client)->leads($client->raw()->linked_leads_id)->run();
		}
		return $result;
	}
}
