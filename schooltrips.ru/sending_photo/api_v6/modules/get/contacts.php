<?php
/**
 * Получение контактов
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class ContactsGet extends EntityGet
{
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct($instance);
		$this->setKey('contacts');
		$this->setEntity('contacts');
		
	}
	
    /**
     * Поиск по типу
     */
    public function type($client_type)
    {
		$this->setParam('type', $client_type);
		return $this;
	}
	
    /**
     * Поиск контактов
     */
    public function search($from, $name = false, $max = 0, $strict = true)
    {
        if ($max > 0) {
            $this->setParam('limit_rows', $max);
        }
		$query = $from;
        if ($name) {
            $query = $this->format($from, $name);
			if (empty($query)) {
				throw new \Exception('Invalid search query "'.$from.'" from cfield "'.$name.'": '.$query.'.');
			}
        }
        $this->setParam('query', $query);
        $result = $this->run();
        $contacts = null;
		if ($max == -1) {
			$contacts = array();
		}
        if (!empty($result)) {
            if (!$name) {
                return $result;
            }
            foreach ($result as $contact) {

				if ($name == 'Название') {
					if ($strict) {
						if ($this->format($contact->raw()->name, $name) == $query) {
							if ($max > -1) {
								return $contact;
							}
							$contacts[] = $contact;
						}
					} else {
						if (stripos($this->format($contact->raw()->name, $name), $query) !== false) {
							if ($max > -1) {
								return $contact;
							}
							$contacts[] = $contact;
						}
					}
					continue;
				}
				$customs = $contact->customByName($name, false);
                if (empty($customs->values)) {
                    continue;
                }
                foreach ($customs->values as $cf) {
					if ($strict) {
						if ($this->format($cf->value, $name) == $query) {
							if ($max > -1) {
								return $contact;
							}
							$contacts[] = $contact;
						}
					} else {
						if (stripos($this->format($cf->value, $name), $query) !== false) {
							if ($max > -1) {
								return $contact;
							}
							$contacts[] = $contact;
						}
					}
                }
            }
        }
        return $contacts;
    }
	
    /**
     * Форматирование поискового запроса
     */
    public function format($query, $name = false)
    {
        if ($name == 'Телефон' && strlen($query) >= 10) {
            $query = substr(preg_replace('#[^0-9]+#Uis', '', $query), -10);
        }
		if ($name == 'Email') {
			$query = mb_strtoupper($query);
		}
		if ($name == 'Название') {
			$query = mb_strtoupper($query);
		}
        return $query;
    }
	
    /**
     * Объект entity
     */
    public function entityObject($entity_data)
    {
		return $this->getEntityObject('contacts', $entity_data, $this->_instance);
	}
}
