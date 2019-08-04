<?php
/**
 * Добавление компаний
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class CompanySet extends EntitySet
{
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct('company', $instance);
	}
	
    /**
     * Добавленные сущности
     */
    public function addedCompanys()
    {
		return $this->request;
	}
	
    /**
     * Добавленная сущность
     */
    public function addedCompany()
    {
		$entity_class = 'Amo\\Company';
		return new $entity_class('company', array_merge(end($this->request), ['type' => 'company']), $this->_instance);
	}
	
    /**
     * Имя компании
     */
    public function name($value)
    {
        if (!empty($value)) $this->setValue('name', $value);
        return $this;
    }

    /**
     * Ключевые слова
     */
    public function tags($value)
    {
        if (!empty($value)) $this->setValue('tags', $value);
        return $this;
    }

    /**
     * Связанные сделки
     */
    public function leads($values)
    {
        if (!is_array($values)) {
            $values = array($values);
        }
        $this->setValue('linked_leads_id', $values);
        return $this;
    }

    /**
     * Номер телефона
     */
    public function phone($values)
    {
        if (!empty($values)) $this->custom('Телефон', $values);
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
        $this->setCustom($name, $values, $replace, $multi);
        return $this;
    }

    /**
     * Электронная почта
     */
    public function email($values)
    {
        if (!empty($values)) $this->custom('Email', $values);
        return $this;
    }

    /**
     * Мгн.сообщения
     */
    public function im($name, $value)
    {
        $this->custom('Мгн. сообщения', array($value, strtoupper($name)));
        return $this;
    }

    /**
     * Ответственный
     */
    public function respId($value)
    {
        if (!empty($value)) $this->setValue('responsible_user_id', $value);
        return $this;
    }

    /**
     * Добавление
     */
    public function run($request = false)
    {
        $this->bind();
        $data = array();
        if ($request) {
			$this->request = $request;
		}
        if (empty($this->request)) {
			return false;
		}
        if (count($this->request) > 300) {
			return $this->mrun($request);
		}
        $data['request']['contacts'][$this->mode] = array_values($this->request);
        $result = $this->_instance->query('post', '/private/api/v2/json/' . $this->entity . '/set', $data);
		
        $this->response = $result->getResp();
		if (isset($this->response->{'contacts'}->{$this->mode})) {
			
			$this->response = $this->response->{'contacts'}->{$this->mode};
			foreach ($this->response as $val) {
				if (isset($val->request_id) && isset($val->id)) {
					$this->request[$val->request_id]['id'] = $val->id;
					if (isset($val->last_modified)) {
						$this->request[$val->request_id]['last_modified'] = $val->last_modified;
					} else {
						$this->request[$val->request_id]['last_modified'] = time();
					}
				}
			}
			if (isset($this->response[0]->id)) {
				return $this->response[0]->id;
			}
		}
		$this->error = (object)array(
			'code' => $result->getCode(),
			'text' => $result->getError(),
		);
		return false;
    }
}