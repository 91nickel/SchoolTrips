<?php

/**
 * Класс примечаний
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class Notes extends Entity
{
    /**
     * Тип примечания
     */
    protected $note_type;
    /**
     * Текст примечания
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
     * Доступно ли для редактирования
     */
    protected $editable;

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
		$types = array(1 => 'contacts', 2 => 'leads', 3 => 'company', 4 => 'tasks');
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
        $types = array(1 => 'contact', 2 => 'lead', 3 => 'company', 4 => 'task');
        return $types[$this->element_type];
    }

    /**
     * Кто звонил
     */
    public function callFrom()
    {
        if ($data = $this->text(true)) {

            if (isset($data->from)) {
                return $data->from;
            }
        }
        return null;
    }

    /**
     * Получение текста примечания
     */
    public function text($json = false)
    {
        if ($json) {
            if ($data = json_decode($this->text)) {
                return $data;
            }
            return null;
        }
        return $this->text;
    }
	
    /**
     * Телефония
     */
    public function callSrc()
    {
        if ($data = $this->text(true)) {

            if (isset($data->SRC)) {
                return $data->SRC;
            }
        }
        return '';
    }

    /**
     * Кому звонил
     */
    public function callPhone()
    {
        if ($data = $this->text(true)) {

            if (isset($data->PHONE)) {
                return $data->PHONE;
            }
        }
        return '';
    }
	
    /**
     * Статус звонка
     */
    public function callStatus()
    {
        if ($data = $this->text(true)) {

            if (isset($data->call_status)) {
                return $data->call_status;
            }
        }
        return 0;
    }

    /**
     * Длительность звонка
     */
    public function callDuration()
    {
        if ($data = $this->text(true)) {

            return $data->DURATION;
        }
        return 0;
    }

    /**
     * Старый статус - имя
     */
    public function oldStatusName()
    {
        if ($status = $this->oldStatus()) {

            return $status->name;
        }
        return null;
    }

    /**
     * Старый статус - объект
     */
    public function oldStatus()
    {
		$leadinfo = new Leadinfo($this->_instance);
        if ($status = $leadinfo->statusFromId($this->oldStatusId())) {

            return $status;
        }
        return null;
    }
	
    /**
     * Старая воронка
     */
    public function oldPipelineId()
    {
        if ($data = $this->text(true)) {

            return $data->PIPELINE_ID_OLD;
        }
        return 0;
    }

    /**
     * Старый статус - id
     */
    public function oldStatusId()
    {
        if ($data = $this->text(true)) {

            return $data->STATUS_OLD;
        }
        return 0;
    }

    /**
     * Новый статус - имя
     */
    public function newStatusName()
    {
        if ($status = $this->newStatus()) {

            return $status->name;
        }
        return null;
    }

    /**
     * Новый статус - объект
     */
    public function newStatus()
    {
		$leadinfo = new Leadinfo($this->_instance);
        if ($status = $leadinfo->statusFromId($this->newStatusId())) {

            return $status;
        }
        return null;
    }

    /**
     * Новый статус
     */
    public function newStatusId()
    {
        if ($data = $this->text(true)) {

            return $data->STATUS_NEW;
        }
        return 0;
    }
	
    /**
     * Новая воронка
     */
    public function newPipelineId()
    {
        if ($data = $this->text(true)) {

            return $data->PIPELINE_ID_NEW;
        }
        return 0;
    }

    /**
     * Можно ли редактировать
     */
    public function isEditable()
    {
        return $this->editable == 'Y' ? true : false;
    }
}