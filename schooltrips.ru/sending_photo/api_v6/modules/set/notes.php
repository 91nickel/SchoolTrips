<?php
/**
 * Добавление примечаний
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class NotesSet extends EntitySet
{
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct('notes', $instance);
	}
	
    /**
     * Текст примечания
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
     * Тип примечания
     */
    public function type($value)
    {
        if (!empty($value)) $this->setValue('note_type', $value);
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
     * Ответственный
     */
    public function respId($value)
    {
        if (!empty($value)) $this->setValue('responsible_user_id', $value);
        return $this;
    }
}
