<?php
/**
 * Обновление примечаний
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class NotesUpdate extends EntityUpdate
{
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct('notes', $instance);
	}
	
    /**
     * Сущность для обновления
     */
    public function set($note = null, $element_type = null)
    {
        if (is_numeric($note) && !is_null($element_type)) {
            $note = $this->_instance->notes()->get()->byId($element_type, $note);
        }
        if (!is_object($note)) {
            return false;
        }
        $this->note = json_decode(json_encode($note->raw()), true);
		$this->setValue('id', $this->note['id']);
		$this->setValue('element_id', $this->note['element_id']);
		$this->setValue('element_type', $this->note['element_type']);
		$this->setValue('note_type', $this->note['note_type']);
		$this->setValue('text', $this->note['text']);
        $this->setModif();
		return $this;
    }

    /**
     * Время изменения
     */
    public function setModif($time = false)
    {
        if (!$time && $this->note['last_modified'] < time()) {
            $time = time();
        } elseif (!$time) {
            $time = $this->note['last_modified'] += 3;
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
     * Дата создания
     */
    public function created($value)
    {
        if (!empty($value)) $this->setValue('date_create', $value);
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
