<?php
/**
 * amoCRM класс
 * Получение информации о менеджерах
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 * @version 1.01
 */
namespace Amo;

class Userinfo
{
    private $_instance,
			$user_id = array(),
			$user_names = array(),
			$user_logins = array(),
			$user_groups = array(),
			$deleted = array(
				'id' => -1,
				'name' => 'Удален',
				'last_name' => 'Удален',
				'fullname' => 'Удален',
				'login' => 'deleted',
				'phone_number' => '',
			);

    public function __construct(\Amo\CRM $instance)
    {
		$this->_instance = $instance;
		$current = $this->_instance->getCurrent();
		$this->user_groups = $current->groups;
		
        foreach ($current->users as $user) {
            $user->fullname = $user->name;
            if (!empty($user->last_name)) {
				$user->fullname = $user->name . ' ' . $user->last_name;
			}
			if (!isset($user->phone_number)) {
				$user->phone_number = '';
			}
            $this->user_id[$user->id] = $user;
            $this->user_logins[$user->login] = $user->id;
            $this->user_name[trim($user->name)] = $user->id;
        }
    }

	/**
     * Получение информации о группах менеджеров
     */
    public function getGroups()
    {
		return $this->user_groups;
	}
	
    /**
     * Получение информации о менеджере по имени
     */
    public function byName($user_name, $info = true)
    {
		$user_name = trim($user_name);
        if (isset($this->user_name[$user_name])) {
            if ($info) {
				return $this->byId($this->user_name[$user_name]); 
			} else {
				return $this->user_name[$user_name];
			}
        }
        return false;
    }

    /**
     * Получение информации о менеджере по id
     */
    public function byId($user_id)
    {
        if (isset($this->user_id[$user_id])) return $this->user_id[$user_id];
        return (object)$this->deleted;
    }

    /**
     * Получение информации о менеджере по логину
     */
    public function byLogin($user_login)
    {
        if (isset($this->user_logins[$user_login])) {
			return $this->byId($this->user_logins[$user_login]);
		}
        return (object)$this->deleted;
    }

    /**
     * Получение массива объектов пользователей
     */
    public function all()
    {
        return $this->user_id;
    }
	
    /**
     * Получение массива объектов пользователей
     */
    public function users()
    {
        return $this->user_id;
    }

    /**
     * Получение массива пользователей
     */
    public function userList()
    {
        $users = array();
        foreach ($this->user_id as $user_id => $user) {
            $users[$user_id] = $user->name;
        }
        return $users;
    }
}
