<?php
/**
 * Добавление контактов
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class ContactsSet extends EntitySet
{
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct('contacts', $instance);
	}
	
    /**
     * Добавленные сущности
     */
    public function addedContacts()
    {
		return $this->request;
	}
	
    /**
     * Добавленная сущность
     */
    public function addedContact()
    {
		$entity_class = 'Amo\\Contacts';
		return new $entity_class('contacts', array_merge(end($this->request), ['type' => 'contact']), $this->_instance);
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
     * Связанные компании
     */
    public function companyName($value)
    {
        if (!empty($value)) $this->setValue('company_name', $value);
        return $this;
    }

    /**
     * Связанные компании
     */
    public function company($value)
    {
        if (!empty($value)) $this->setValue('linked_company_id', $value);
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
}
