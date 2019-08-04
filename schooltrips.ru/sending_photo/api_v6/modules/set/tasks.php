<?php
/**
 * Добавление задач
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class TasksSet extends EntitySet
{
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct('tasks', $instance);
	}
	
    /**
     * Добавленная сущность
     */
    public function addedTask()
    {
		$entity_class = 'Amo\\Tasks';
		return new $entity_class('task', end($this->request), $this->_instance);
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
     * Тип задачи по имени
     */
    public function type($value)
    {
        if (isset($this->task_type[$value])) $this->typeId($this->task_type[$value]);
        return $this;
    }
	
    /**
     * Тип задачи по id
     */
    public function typeId($value)
    {
		if (!empty($value)) $this->setValue('task_type', $value);
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
     * Пользователь
     */
    public function createdUser($value)
    {
        if (!empty($value)) $this->setValue('created_user_id', $value);
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
