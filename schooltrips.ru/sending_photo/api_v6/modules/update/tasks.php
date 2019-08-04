<?php
/**
 * Обновление задач
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class TasksUpdate extends EntityUpdate
{
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct('tasks', $instance);
	}
	
    /**
     * Сущность для обновления
     */
    public function set($task = null)
    {
        if (is_numeric($task)) {
            $task = $this->_instance->tasks()->get()->byId($task);
        }
        if (!is_object($task)) {
            return false;
        }
        $this->task = json_decode(json_encode($task->raw()), true);
		$this->setValue('id', $this->task['id']);
		$this->setValue('element_id', $this->task['element_id']);
		$this->setValue('element_type', $this->task['element_type']);
		$this->setValue('task_type', $this->task['task_type']);
		$this->setValue('text', $this->task['text']);
        $this->setModif();
        return $this;
    }

    /**
     * Время изменения
     */
    public function setModif($time = false)
    {
        if (!$time && $this->task['last_modified'] < time()) {
            $time = time();
        } elseif (!$time) {
            $time = $this->task['last_modified'] += 3;
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
     * Текст задачи
     */
    public function text($value)
    {
        if (!empty($value)) $this->setValue('text', $value);
        return $this;
    }

    /**
     * Тип привязываемого елемента
     */
    public function elemType($value)
    {
        $element = array('contact' => 1, 'lead' => 2, 'company' => 3);
        if (!empty($value)) $this->setValue('element_type', $element[$value]);
        return $this;
    }

    /**
     * ID привязываемого елемента
     */
    public function elemId($value)
    {
        if (!empty($value)) $this->setValue('element_id', $value);
        return $this;
    }

    /**
     * Тип задачи
     */
    public function type($value)
    {
        if (isset($this->task_type[$value])) $this->setValue('task_type', $this->task_type[$value]);
        return $this;
    }

    /**
     * Время на задачу в минутах
     */
    public function toTime($value)
    {
        $time = time() + ($value * 60);
        if (!empty($value)) $this->setValue('complete_till', $time);
        return $this;
    }

    /**
     * Время на задачу timestamp
     */
    public function setTime($value)
    {
        if (!empty($value)) $this->setValue('complete_till', $value);
        return $this;
    }

    /**
     * Время на задачу Y-m-d H:i:s
     */
    public function setDate($value)
    {
        if (!empty($value)) $this->setValue('complete_till', strtotime($value));
        return $this;
    }

    /**
     * Дата создания
     */
    public function created($value)
    {
        if (!empty($value)) $this->setValue('date_create', $value);
        return $this;
    }

    /**
     * Статус задачи
     */
    public function status($value)
    {
        if (!empty($value)) $this->setValue('status', $value);
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
