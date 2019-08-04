<?php
/**
 * Класс задач
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class Tasks extends Entity
{
    /**
     * Тип задачи
     */
    protected $task_type;
    /**
     * Текст задачи
     */
    protected $text;
    /**
     * Тип привязанного элемента
     */
    protected $element_type;
    /**
     * Идентификатор привязанного элемента
     */
    protected $element_id;
    /**
     * Статус задачи
     */
    protected $status;
    /**
     * Метка времени завершения
     */
    protected $complete_till;
    /**
     * Результат задачи
     */
    protected $result;

    //=======================================================

    public function __construct($ename, $entity, \Amo\CRM $instance)
    {
        parent::__construct($ename, $entity, $instance);
    }

    /**
     * Получение id привязанного элемента
     */
    public function elementId()
    {
        return $this->element_id;
    }
	
    /**
     * Получение привязанного элемента
     */
    public function entity()
    {
		$types = array(1 => 'contacts', 2 => 'leads', 3 => 'company');
		if (!isset($types[$this->element_type])) {
			return null;
		}
        return $this->_instance->$types[$this->element_type]()->get()->byId($this->element_id);
    }

    /**
     * Получение типа привязанного элемента
     */
    public function elementType()
    {
        $types = array(0 => 'none', 1 => 'contact', 2 => 'lead', 3 => 'company');
        return $types[$this->element_type];
    }

    /**
     * Получение текста задачи
     */
    public function text()
    {
        return $this->text;
    }

    /**
     * Тип задачи - id
     */
    public function typeId()
    {
        return $this->task_type;
    }

    /**
     * Тип задачи
     */
    public function type()
    {
		$taskinfo = new Taskinfo($this->_instance);
        if ($type = $taskinfo->typeFromId($this->task_type)) {
            return $type;
        }
        return null;
    }

    /**
     * Статус задачи
     */
    public function status()
    {
        return $this->status;
    }

    /**
     * Выполнена ли задача
     */
    public function isCompleted()
    {
        return (bool)$this->status;
    }

    /**
     * Получение метки времени планируемого завершения
     */
    public function completeTime()
    {
        return $this->complete_till;
    }

    /**
     * Получение даты планируемого завершения
     */
    public function completeDate($format = 'Y-m-d H:i:s')
    {
        return Fn::monthRu(date($format, $this->complete_till));
    }
	
    /**
     * Получение результата по задаче
     */
    public function result()
    {
        return $this->result;
    }
}