<?php
/**
 * Обновление контактов
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class ContactsUpdate extends EntityUpdate
{
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct('contacts', $instance);
	}
	
    /**
     * Данные для обновления
     */
    public function setRaw(Array $contact)
    {
		$this->contact = $contact;
		$this->id($this->contact['id']);
        $this->setModif();
		
		if (!empty($this->contact['linked_leads_id'])) {
			$this->leads($this->contact['linked_leads_id']);
		}
        if (empty($this->contact['custom_fields'])) {
			return $this;
		}
        foreach ($this->contact['custom_fields'] as $cf_field) {

            $this->contact['update']['custom'][$cf_field['name']] = array();
            $cf_values = array();

            foreach ($cf_field['values'] as $cf_item) {

                $field = array($cf_item['value']);
                if (isset($cf_item['enum']) && isset($this->enum[$cf_item['enum']]) && $this->enum[$cf_item['enum']] != $cf_item['value']) {
                    $field[1] = $this->enum[$cf_item['enum']];
                } else {
                    $field[1] = 'OTHER';
                }
                $this->contact['update']['custom'][$cf_field['name']][] = $field;
                $cf_values[] = $field;

                if ($cf_field['name'] == 'Мгн. сообщения') {
                    $this->contact['setted'][$cf_field['name']][$field[1]][$field[0]] = 1;
                } else {
                    $this->contact['setted'][$cf_field['name']][(string)$cf_item['value']] = 1;
                }
            }
            $this->custom($cf_field['name'], $cf_values);
        }
        return $this;
	}
	
    /**
     * Сущность для обновления
     */
    public function set($contact = null)
    {
        if (is_numeric($contact)) {
            $contact = $this->_instance->contacts()->get()->byId($contact);
        }
        if (!is_object($contact)) {
			throw new \Exception('Entity data must be numeric or object');
        }
        return $this->setRaw(json_decode(json_encode($contact->raw()), 1));
    }

    /**
     * Время изменения
     */
    public function setModif($time = false)
    {
        if (!$time && $this->contact['last_modified'] < time()) {
            $time = time();
        } elseif (!$time) {
            $time = $this->contact['last_modified'] += 10;
        }
        $this->setValue('last_modified', $time);
        return $this;
    }

    /**
     * Идентификатор контакта
     */
    public function id($id)
    {
        if (!empty($id)) $this->setValue('id', $id);
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
     * Мгн.сообщения
     */
    public function im($name, $value, $replace = false)
    {
        $name = strtoupper($name);
        if ($replace || !isset($this->contact['setted']['Мгн. сообщения'][$name][$value])) {

            $this->custom('Мгн. сообщения', array($value, $name), $replace);
        }
        return $this;
    }

    /**
     * Имя контакта
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
        if (!empty($this->contact['tags'])) {

            $tags = array();
            foreach ($this->contact['tags'] as $tag) {
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
            if (!in_array($lead_id, $this->contact['linked_leads_id'])) {
                $this->contact['linked_leads_id'][] = $lead_id;
            }
        }
        $this->setValue('linked_leads_id', $this->contact['linked_leads_id']);
        return $this;
    }

    /**
     * Связанные компании
     */
    public function company($value)
    {
        if (!empty($value)) {
			$this->setValue('linked_company_id', $value);
		}
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
            if (isset($this->contact['setted']['Телефон'][$value[0]])) continue;
            $this->contact['update']['custom']['Телефон'][] = $value;
        }
        $this->custom('Телефон', $this->contact['update']['custom']['Телефон'], true);
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
            if (isset($this->contact['setted']['Email'][$value[0]])) continue;
            $this->contact['update']['custom']['Email'][] = $value;
        }
        $this->custom('Email', $this->contact['update']['custom']['Email'], true);
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
}
