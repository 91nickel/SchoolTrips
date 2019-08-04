<?php
/**
 * Обновление сделок
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class LeadsUpdate extends EntityUpdate
{
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct('leads', $instance);
    }
	
    /**
     * Данные для обновления
     */
    public function setRaw(Array $lead)
    {
		$this->lead = $lead;
        $this->setModif();
        $this->id($this->lead['id']);
		
        if (empty($this->lead['custom_fields'])) {
			return $this;
		}
        foreach ($this->lead['custom_fields'] as $cf_field) {

            $this->lead['update']['custom'][$cf_field['name']] = array();

            foreach ($cf_field['values'] as $cf_item) {

                $this->lead['setted'][$cf_field['name']][$cf_item['value']] = 1;
                $field = array($cf_item['value']);

                if (isset($cf_item['enum']) && isset($this->enum[$cf_item['enum']])) {

                    $field[1] = $this->enum[$cf_item['enum']];
                }
                $this->lead['update']['custom'][$cf_field['name']][] = $field;
            }
        }
        return $this;
	}

    /**
     * Сущность для обновления
     */
    public function set($lead = null)
    {
        if (is_numeric($lead)) {
            $lead = $this->_instance->leads()->get()->byId($lead);
        }
        if (!is_object($lead)) {
			throw new \Exception('Entity data must be numeric or object');
        }
		return $this->setRaw(json_decode(json_encode($lead->raw()), 1));
	}
	
    /**
     * Время изменения
     */
    public function setModif($time = false)
    {
        if (isset($this->lead['last_modified'])) {
            if (!$time && $this->lead['last_modified'] < time()) {
                $time = time();
            } elseif (!$time) {
                $time = $this->lead['last_modified'] += 10;
            }
        } else {
            if (!$time) $time = time();
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
     * Название сделки
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
        if (!empty($this->lead['tags'])) {
            $tags = array();
            foreach ($this->lead['tags'] as $tag) {
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
     * Теги
     */
    public function price($value)
    {
        $this->setValue('price', (int)$value);
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
    public function status($status, $pipeline = false)
    {
        if ($pipeline && isset($this->lead_pipeline[$pipeline])) {

            $this->setValue('pipeline_id', $this->lead_pipeline[$pipeline]['id']);
            $this->setValue('status_id', $this->lead_pipeline[$pipeline]['status'][$status]);

        } elseif (isset($this->lead_status[$status])) {

            $this->setValue('status_id', $this->lead_status[$status]);
        }
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
     * Ответственный
     */
    public function respId($value)
    {
        if (!empty($value)) $this->setValue('responsible_user_id', $value);
        return $this;
    }
}
