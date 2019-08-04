<?php
/**
 * Удаление задач
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class TasksRemove 
{
	private $_instance,
			$task_id;
	
    public function __construct($task_id, \Amo\CRM $instance)
    {
		$this->_instance = $instance;
		$this->task_id = $task_id;
	}
	
    /**
     * Выполнить удаление
     */
    public function run()
    {
		$request = array(
			'ACTION' => 'TASK_DELETE',
			'ID' => $this->task_id,
		);
        $result = $this->_instance->query('post', '/private/notes/edit2.php', $request);
		$data = $result->getData();
		if ($data->status == 'ok') {
			return $data->response->notifications_array->notifications;
		}
		return false;
    }
}