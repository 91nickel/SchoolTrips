<?php
/**
 * Получение компаний
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class CompanyGet extends EntityGet
{
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct($instance);
		$this->setKey('contacts');
		$this->setEntity('company');
		$this->setParam('type', 'company');
	}
	
    /**
     * Поиск компаний
     */
    public function search($from, $name = false, $max = 0)
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
        $companys = null;

        if (!empty($result)) {
            if (!$name) {
                return $result;
            }
            if ($max == -1) {
                $companys = array();
            }
            foreach ($result as $company) {

				if ($name == 'Название') {
					if ($strict) {
						if ($this->format($company->raw()->name, $name) == $query) {
							if ($max > -1) {
								return $company;
							}
							$companys[] = $company;
						}
					} else {
						if (stripos($this->format($company->raw()->name, $name), $query) !== false) {
							if ($max > -1) {
								return $company;
							}
							$companys[] = $company;
						}
					}
					continue;
				}
                $customs = $company->customByName($name, false);
                if (empty($customs->values)) {
                    continue;
                }
                foreach ($customs->values as $cf) {
                    if ($this->format($cf->value, $name) == $query) {
                        if ($max > -1) {
                            return $company;
                        }
                        $companys[] = $company;
                    }
                }
            }
        }
        return $companys;
    }
	
    /**
     * Форматирование поискового запроса
     */
    public function format($query, $name = false)
    {
        if ($name == 'Телефон' && strlen($query) >= 10) {
            $query = substr( preg_replace('#[^0-9]+#Uis', '', $query ), -10);
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
		return $this->getEntityObject('company', $entity_data, $this->_instance);
	}
}
