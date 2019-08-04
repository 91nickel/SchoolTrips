<?php
/**
 * Добавление в amoCRM
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class EntitySet
{
    protected $_instance,
			  $entity,
			  $temp = array(),
			  $request = array(),
			  $custom = array(),
			  $mode = 'add',
			  $action = 'set',
			  $enum = array(),
			  $cf_enums = array(),
			  $lead_status = array(),
			  $lead_pipeline = array(),
			  $note_type = array(),
			  $task_type = array(),
			  $response,
			  $error;

    public function __construct($entity, \Amo\CRM $instance)
    {
		$this->_instance = $instance;
        $this->setEntity($entity);
        $this->init();
    }

    /**
     * Установка сущности
     */
    protected function setEntity($entity)
    {
        $this->entity = $entity;
		return $this;
    }
	
    /**
     * Установка типа запросов
     */
    protected function setMode($mode)
    {
        $this->mode = $mode;
		return $this;
    }
	
    /**
     * Установка действия
     */
    protected function setAction($action)
    {
        $this->action = $action;
		return $this;
    }

    /**
     * Установка временных данных для отправки
     */
    public function setValue($key, $value)
    {
        $this->temp[$key] = $value;
		return $this;
    }

    /**
     * Установка временных custom данных для отправки
     */
    public function setCustom($name, $values, $replace = false, $multi = false)
    {
        if (!isset($this->custom[$name])) {
            throw new \Exception('Custom field "'.$name.'" not found');
        }
		if (!isset($this->temp['custom_fields'])) {
			$this->temp['custom_fields'] = [];
		}
		if ($this->custom[$name]->type_id == 5 || $multi) {
			return $this->setMultiCustom($name, $values, $replace);
		}
        if (!$multi && !is_array($values[0])){
			$values = array($values);
		}
        $custom_values = array();
        foreach ($values as $val) {

			if (empty($val[0])) {
				continue;
			}
            if (!isset($val[1])) {
				$val[1] = 'OTHER';
			}
			$custom_values[] = array(
				'value' => $val[0],
				'enum' => $val[1]
			);
        }
		if (empty($custom_values)) {
			return $this;
		}
        if ($replace) {
            if (!empty($this->temp['custom_fields'])) {
                foreach ($this->temp['custom_fields'] as $k => $c_field) {
                    if ($c_field['id'] == $this->custom[$name]->id) {
						unset($this->temp['custom_fields'][$k]);
					}
                }
            }
        }
        $this->temp['custom_fields'][] = array(
            'id' => $this->custom[$name]->id,
            'name' => $name,
            'values' => $custom_values
        );
		return $this;
    }

    /**
     * Установка временных multi custom данных для отправки
     */
    public function setMultiCustom($name, $values, $replace = false)
    {
        if (!isset($this->custom[$name])) {
            throw new \Exception('Multi custom field "'.$name.'" not found');
        }
		if (!isset($this->temp['custom_fields'])) {
			$this->temp['custom_fields'] = [];
		}
		$cf_setted_key = false;
		
		foreach ($this->temp['custom_fields'] as $k => $c_field) {
			if ($c_field['id'] == $this->custom[$name]->id) {
				$cf_setted_key = $k;
			}
		}
        if ($cf_setted_key && $replace) {
			unset($this->temp['custom_fields'][$cf_setted_key]);
			$cf_setted_key = false;
        }
		$custom_values = [];

		if ($cf_setted_key) {
			foreach ($this->temp['custom_fields'][$cf_setted_key]['values'] as $cf_setted_val) {
				$custom_values[]= $cf_setted_val;
			}
			unset($this->temp['custom_fields'][$cf_setted_key]);
			$cf_setted_key = false;
		}
        foreach ($values as $val) {
			if (is_array($val)) {
				$val = $val[0];
			}
			if (empty($val)) {
				continue;
			}
			if (in_array($val, $this->cf_enums[$this->custom[$name]->id])) {
				$cf_val = array_search($val, $this->cf_enums[$this->custom[$name]->id]);
				if (!in_array($cf_val, $custom_values)) {
					$custom_values[] = $cf_val;
				}
			}
		}
        $this->temp['custom_fields'][] = array(
            'id' => $this->custom[$name]->id,
            'name' => $name,
            'values' => $custom_values
        );
		return $this;
	}
	
    /**
     * Id запроса
     */
    public function requestId($value)
    {
        if (!empty($value)) {
			$this->setValue('request_id', $value);
		}
        return $this;
    }

    /**
     * Массовое добавление
     */
    public function mrun($req = false)
    {
        $this->bind();
        $p = 0;
        $i = 0;

        $parts = array();
        $results = array();

        foreach ($this->request as $request) {
            $parts[$p][] = $request;
            if ($i == 300) {
                $i = 0;
                $p++;
            } else {
                $i++;
            }
        }
        foreach ($parts as $part) {
            if (!empty($part)) {
                $results[] = $this->run($part);
            }
        }
        return $results;
    }

    /**
     * Множественное добавление
     */
    public function bind()
    {
		if (!empty($this->temp)) {
			if (!isset($this->temp['request_id'])) {
				$this->temp['request_id'] = count($this->request);
			}
            $this->request[$this->temp['request_id']] = $this->temp;
            $this->temp = array();
        }
        return $this->request;
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
        $data['request'][$this->entity][$this->mode] = array_values($this->request);
        $result = $this->_instance->query('post', '/private/api/v2/json/' . $this->entity . '/'.$this->action, $data);
		
        $this->response = $result->getResp();
		if (isset($this->response->{$this->entity}->{$this->mode})) {
			
			$this->response = $this->response->{$this->entity}->{$this->mode};
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
			if (is_array($this->response) && isset($this->response[0]->id)) {
				return $this->response[0]->id;
			}
		}
		$this->error = (object)array(
			'code' => $result->getCode(),
			'text' => $result->getError(),
		);
		return false;
    }

    /**
     * Инициализация
     */
    protected function init()
    {
		$current = $this->_instance->getCurrent();
        $custom_entity = $this->entity;
        if ($this->entity == 'company') $custom_entity = 'companies';
		
        // дополнительные поля
        if (isset($current->custom_fields->{$custom_entity})) {

            foreach ($current->custom_fields->{$custom_entity} as $custom_field) {

                if (!empty($custom_field->enums)) {

                    foreach ($custom_field->enums as $eid => $enam) {
                        $eid = (int)$eid;
                        $this->enum[$eid] = $enam;
                    }
					$this->cf_enums[$custom_field->id] = (array)$custom_field->enums;
                }
                $this->custom[$custom_field->name] = $custom_field;
            }
        }

        // статусы сделок
        foreach ($current->leads_statuses as $least) {

            $this->lead_status[$least->name] = $least->id;
        }

        // воронки
        if (!empty($current->pipelines)) {

            foreach ($current->pipelines as $pipeline) {

                $this->lead_pipeline[$pipeline->name] = array('id' => $pipeline->id);

                foreach ($pipeline->statuses as $ppl_status) {

                    $this->lead_pipeline[$pipeline->name]['status'][$ppl_status->name] = $ppl_status->id;
                }
            }
        }

        // типы примечаний
        foreach ($current->note_types as $noty) {

            $this->note_type[$noty->code] = $noty->id;
        }

        // типы задач
        foreach ($current->task_types as $tasky) {

            $this->task_type[$tasky->name] = $tasky->id;
        }
    }
	
    /**
     * Информация об ответе
     */
    public function getResponse()
    {
		return $this->response;
	}
	
    /**
     * Информация об ошибках
     */
    public function getError()
    {
		return $this->error;
	}
}
