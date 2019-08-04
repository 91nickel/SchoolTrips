<?php
/**
 * Удаление примечаний
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class NotesRemove 
{
	private $_instance,
			$note_id;
	
    public function __construct($note_id, \Amo\CRM $instance)
    {
		$this->_instance = $instance;
		$this->note_id = $note_id;
	}
	
    /**
     * Выполнить удаление
     */
    public function run()
    {
		$request = array(
			'ACTION' => 'NOTE_DELETE',
			'ID' => $this->note_id,
		);
        $result = $this->_instance->query('post', '/private/notes/edit2.php', $request );
		$data = $result->getData();
		if ($data->status == 'ok') {
			return $data->response->notifications_array->notifications;
		}
		return false;
    }
}