<?php
/**
 * Получение задач
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class TasksGet extends EntityGet
{
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct($instance);
		$this->setKey('tasks');
		$this->setEntity('tasks');
	}

    /**
     * Поиск по типу задачи
     */
    public function search($element_type = false)
    {
        if ($element_type) {
            $this->setParam('type', $element_type);
        }
        if ($data = $this->run()) {
            return $data;
        }
        return null;
    }

    /**
     * Получение по ID сделки
     */
    public function byLeadId($id)
    {
		$this->setParam('type', 'lead');
        $this->setParam('element_id', $id);

        if ($data = $this->run()) {
            return $data;
        }
        return null;
    }

    /**
     * Получение по ID контакта
     */
    public function byContactId($id)
    {
		$this->setParam('type', 'contact');
        $this->setParam('element_id', $id);

        if ($data = $this->run()) {
            return $data;
        }
        return null;
    }
	
    /**
     * Получение по ID компании
     */
    public function byCompanyId($id)
    {
		$this->setParam('type', 'company');
        $this->setParam('element_id', $id);

        if ($data = $this->run()) {
            return $data;
        }
        return null;
    }

    /**
     * Получение по ответственному пользователю
     */
    public function byRespId($respId, $type = false)
    {
        if ($type) {
            $this->setParam('type', $type);
        }
        $this->setParam('responsible_user_id', $respId);

        if ($data = $this->run()) {
            return $data;
        }
        return null;
    }
}