<?php
/**
 * Получение примечаний
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class NotesGet extends EntityGet
{
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct($instance);
		$this->setKey('notes');
		$this->setEntity('notes');
	}
	
    /**
     * Поиск по типу примечания
     */
    public function search($element_type, $type_id)
    {
        if (empty($element_type) || empty($type_id)) {
            return false;
        }
        $this->setParam('type', $element_type);
        $this->setParam('note_type', $type_id);

        if ($data = $this->run()) {
            return $data;
        }
        return null;
    }

    /**
     * Получение по ID
     */
    public function byId($id, $element_type = null)
    {
        if (empty($element_type) || empty($id)) {
            return false;
        }
        $count = 1;
        if (is_array($id)) {
            $count = count($id);
        }
        $this->setParam('limit_rows', $count);
		$this->setParam('type', $element_type);
		$this->setParam('id', $id);
		
        if ($data = $this->run()) {
            if (is_array($data) && !is_array($id) && count($data) === 1) {
				return $data[0];
			}
            return $data;
        }
        return null;
    }
}
