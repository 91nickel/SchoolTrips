<?php
/**
 * amoCRM класс
 * Класс сущности
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class Entity
{
    /**
     * Данные сущности
     */
    protected $raw;
    /**
     * Название сущности
     */
    protected $entity;
    /**
     * ID сущности
     */
    protected $id;
    /**
     * Ответственный
     */
    protected $responsible_user_id;
    /**
     * Создатель
     */
    protected $created_user_id;
    /**
     * Дата создания
     */
    protected $date_create;
    /**
     * Дата последнего изменения
     */
    protected $last_modified;

    //=======================================================

    public function __construct($ename, &$entity, \Amo\CRM &$instance)
    {
		$this->_instance = $instance;
		//$entity = json_decode(json_encode($entity));
        foreach ($entity as $field => $value) {

            $this->$field = $value;
        }
        $this->entity = $ename;
		$this->raw = $entity;
    }
	
    /**
     * Обращение к данным
     */
    public function raw()
    {
        return $this->raw;
    }

    /**
     * Получение ID сущности
     */
    public function id()
    {
        return (int)$this->id;
    }

    /**
     * Получение метки времени изменения
     */
    public function modifiedTime()
    {
        return $this->last_modified;
    }

    /**
     * Получение даты изменения
     */
    public function modifiedDate($format = 'Y-m-d H:i:s')
    {
        return Fn::monthRu(date($format, $this->last_modified));
    }

    /**
     * Получение метки времени создания
     */
    public function createdTime()
    {
        return $this->date_create;
    }

    /**
     * Получение даты создания
     */
    public function createdDate($format = 'Y-m-d H:i:s')
    {
        return Fn::monthRu(date($format, $this->date_create));
    }

    /**
     * Получение объекта менеджера создателя
     */
    public function createdUser()
    {
		$userinfo = new Userinfo($this->_instance);
        if ($user = $userinfo->byId($this->created_user_id)) {
            return $user;
        }
        return null;
    }

    /**
     * Получение объекта ответственного менеджера
     */
    public function responsibleUser()
    {
		$userinfo = new Userinfo($this->_instance);
        if ($user = $userinfo->byId($this->responsible_user_id)) {
            return $user;
        }
        return (object)array(
            'id' => 0,
            'name' => 'Пользователь удален',
            'login' => 'Пользователь удален',
            'phone_number' => '',
        );
    }
}
