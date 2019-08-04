<?php
/**
 * Обновление компаний
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class CompanyUpdate extends EntityUpdate
{
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct('company', $instance);
	}
	
    /**
     * Данные для обновления
     */
    public function setRaw(Array $company)
    {
		$this->company = $company;
		$this->id($this->company['id']);
        $this->setModif();
		
		if (!isset($this->company['linked_leads_id']) || !is_array($this->company['linked_leads_id'])) {
			$this->company['linked_leads_id'] = [];
		}
		if (!empty($this->company['linked_leads_id'])) {
			$this->leads($this->company['linked_leads_id']);
		}
        if (empty($this->company['custom_fields'])) {
			return $this;
		}
        foreach ($this->company['custom_fields'] as $cf_field) {

            $this->company['update']['custom'][$cf_field['name']] = array();

            foreach ($cf_field['values'] as $cf_item) {

                $this->company['setted'][$cf_field['name']][$cf_item['value']] = 1;
                $field = array($cf_item['value']);
                if (!empty($cf_item['enum']) && isset($this->enum[$cf_item['enum']])) {

                    $field[1] = $this->enum[$cf_item['enum']];
                }
                $this->company['update']['custom'][$cf_field['name']][] = $field;
            }
        }
        return $this;
	}
	
    /**
     * Сущность для обновления
     */
    public function set($company = null)
    {
        if (is_numeric($company)) {
            $company = $this->_instance->company()->get()->byId($company);
        }
        if (!is_object($company)) {
			throw new \Exception('Entity data must be numeric or object');
        }
        return $this->setRaw(json_decode(json_encode($company->raw()), 1));
    }

    /**
     * Время изменения
     */
    public function setModif($time = false)
    {
        if (!$time && $this->company['last_modified'] < time()) {
            $time = time();
        } elseif (!$time) {
            $time = $this->company['last_modified'] += 10;
        }
        $this->setValue('last_modified', $time);
        return $this;
    }

    /**
     * Идентификатор компании
     */
    public function id($id)
    {
        if (!empty($id)) $this->setValue('id', $id);
        return $this;
    }

    /**
     * Название компании
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
        if (!empty($this->company['tags'])) {

            $tags = array();
            foreach ($this->company['tags'] as $tag) {
                $tags[] = $tag['name'];
            }
            if (is_array($value)) {
                $tags = array_merge($tags, $value);
            } elseif (!in_array($value, $tags)) {
                $tags[] = $value;
            }
            $this->setValue('tags', implode(',', array_unique($tags)));
        } else {
            $this->setValue('tags', $value);
        }
        return $this;
    }

    /**
     * Прикрепленные сделки
     */
    public function leads($values)
    {
		if (empty($values)) {
			return $this;
		}
        if (!is_array($values)) {
            $values = array($values);
        }
        foreach ($values as $lead_id) {
            if (!in_array($lead_id, $this->company['linked_leads_id'])) {
                $this->company['linked_leads_id'][] = $lead_id;
            }
        }
        $this->setValue('linked_leads_id', $this->company['linked_leads_id']);
        return $this;
    }

    /**
     * Номер телефона
     */
    public function phone($values)
    {
		if (empty($values)) {
			return $this;
		}
        if (!is_array($values) || !is_array($values[0])) {
            $values = array($values);
        }
        foreach ($values as $value) {

            if (!is_array($value)) {
                $value = array($value);
            }
            if (isset($this->company['setted']['Телефон'][$value[0]])) continue;
            $this->company['update']['custom']['Телефон'][] = $value;
        }
        $this->custom('Телефон', $this->company['update']['custom']['Телефон'], true);
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
		if (empty($values)) {
			return $this;
		}
        if (!is_array($values) || !is_array($values[0])) {
            $values = array($values);
        }
        foreach ($values as $value) {

            if (!is_array($value)) {
                $value = array($value);
            }
            if (isset($this->company['setted']['Email'][$value[0]])) continue;
            $this->company['update']['custom']['Email'][] = $value;
        }
        $this->custom('Email', $this->company['update']['custom']['Email'], true);
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
     * Запрос в амо
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
        $data['request']['contacts']['update'] = $this->request;
        $result = $this->_instance->query('post', '/private/api/v2/json/' . $this->entity . '/set', $data);
		$resp = $result->getResp();
		
        if (!isset($resp->server_time)) {
			throw new \Exception('Error response of "'.$this->mode.'"');
		}
        return true;
    }
}