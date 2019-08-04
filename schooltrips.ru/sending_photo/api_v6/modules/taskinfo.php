<?php
/**
 * amoCRM класс
 * Получение информации о задачах
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 * @version 1.00
 */
namespace Amo;

class Taskinfo
{
    private $types = array(),
			$_instance;

    public function __construct(\Amo\CRM $instance)
	{
		$this->_instance = $instance;
		$current = $this->_instance->getCurrent();
		
        foreach ($current->task_types as $type) {
            if (!empty($type->code)) {
                $this->types[$type->code] = $type;
            } else {
                $this->types[$type->id] = $type;
            }
        }
    }

    /**
     * Тип заадчи по ID типа
     */
    public function typeFromId($type_id)
    {
        if (isset($this->types[$type_id])) {
            return $this->types[$type_id];
        }
        return null;
    }
}
