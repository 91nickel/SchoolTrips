<?php
/**
 * Получение данных из amoCRM
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class EntityGet
{
	protected $_instance,
			  $key,
			  $entity,
			  $max = 0,
			  $typ = 'list',
			  $time_from = false,
			  $search = array(
				'limit_rows' => 500,
				'limit_offset' => 0,
				'filter' => [],
				'order' => [],
			  );
			  
    public function __construct(\Amo\CRM $instance)
    {
		$this->_instance = $instance;
	}
	
    /**
     * Получение по ID
     */
    public function byId($id, $element_type = null)
    {
        if (empty($id)) {
            return false;
        }
        $this->setParam('id', $id);
        $ids = array();

        if (is_array($id)) {
            $max = 300;
            $ids = $id;
            $result = array();

            while (count($ids) > 0) {
                $i = 0;
                $part = array();

                foreach ($ids as $k => $lid) {

                    $part[] = $lid;
                    unset($ids[$k]);
                    $i++;
                    if ($i == $max) break;
                }
                $this->setParam('id', $part);
				$this->setParam('limit_rows', count($part));
                $resp = $this->run();

                foreach ($resp as $item) {
                    $result[] = $item;
                }
            }
            return $result;

        } elseif (!empty($id)) {

            $this->setParam('limit_rows', 1);
            $data = $this->run();

            if (is_array($data) && !is_array($id) && count($data) === 1) {
				return $data[0];
			}
            return $data;
        }
        return null;
    }
	
    /**
     * Установка ключа поиска
     */
    public function setKey($key)
    {
		$this->key = $key;
		return $this;
    }
	
    /**
     * Установка сущности для поиска
     */
    public function setEntity($entity)
    {
		$this->entity = $entity;
		return $this;
    }
	
    /**
     * За период от
     */
    public function timeFrom($time)
    {
		$this->time_from = $time;
		return $this;
    }

    /**
     * Установка типа поиска
     */
    public function setTyp($typ)
    {
		$this->typ = $typ;
		return $this;
    }

    /**
     * Установка параметра поиска
     */
    public function setParam($key, $value)
    {
        $this->search[$key] = $value;
		return $this;
    }

    /**
     * Установка максимума
     */
    public function limit($value)
    {
        $this->search['limit_rows'] = $value;
		return $this->setMax($value);
    }
	
    /**
     * Установка максимума
     */
    public function setMax($value)
    {
        $this->max = $value;
        return $this;
    }
	
    /**
     * Фильтрация
     */
    public function filter($values = [])
    {
		if (is_array($values)) {
			$this->search['filter'] = $values;
		}
        return $this;
    }
	
    /**
     * Сортировка
     */
    public function sort($values = [])
    {
		if (is_array($values)) {
			$this->search['order'] = $values;
		}
        return $this;
    }
	
    /**
     * Получение сущностей
     */
    public function run()
    {
        if ($this->search['limit_rows'] === 1) {
            if (!$data = $this->getData()) return false;
            if (!empty($data->{$this->key}[0])) {
                return $this->result($data->{$this->key}, $this->entity);
            }
        } else {
            $i = $this->search['limit_rows'];
            $geted = array();
            while ($i >= $this->search['limit_rows']) {

                if (!$data = $this->getData()) break;
                $count = count($data->{$this->key});
                $i = $count;

                if ($i > 0) {
                    foreach ($data->{$this->key} as $entity) {
                        $geted[] = $entity;
                    }
                }
                if ($this->search['limit_rows'] == $i && $this->search['limit_rows'] < 500) {
                    break;
                } else {
                    $this->search['limit_offset'] += $i;
                }
                if ($this->max > 0 && count($geted) >= $this->max) {
                    break; // ограничим
                }
            }
            return $this->result($geted, $this->entity);
        }
        return false;
    }

    /**
     * Запрос для получения
     */
    protected function getData()
    {
        $data = $this->_instance->query('get', '/private/api/v2/json/' . $this->entity . '/' . $this->typ, $this->search, $this->time_from);
        if (is_object($data)) {

            return $data->getResp();
        }
        return false;
    }

    /**
     * Обработка ответа
     */
    protected function result($data, $ename)
    {
        $results = (array)$data;
        $entity_class = 'Amo\\' . $ename;

        if (class_exists($entity_class)) {

            foreach ($results as $i => &$entity) {

                $results[$i] = new $entity_class($ename, $entity, $this->_instance);
            }
        }
        return $results;
    }
	
    /**
     * Объект entity
     */
    public function getEntityObject($ename, $data)
    {
		$entity = (array)json_decode(json_encode($data));
		$entity_class = 'Amo\\' . $ename;
		return new $entity_class($ename, $entity, $this->_instance);
	}
}
