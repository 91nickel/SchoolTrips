<?php
/**
 * Обновление в amoCRM
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class EntityUpdate extends EntitySet
{
	protected $mode = 'update';

    /**
     * Обновление
     */
    public function run($request = false)
    {
        $this->bind();
        $data = array();
        if ($request) {
			$this->request = $request;
		}
        if (empty($this->request)) {
			return false;
		}
        $data['request'][$this->entity][$this->mode] = $this->request;
        $result = $this->_instance->query('post', '/private/api/v2/json/' . $this->entity . '/set', $data);
		$this->response = $result->getResp();

        if (!empty($this->response->{$this->entity}->{$this->mode})) {
			$this->response = $this->response->{$this->entity}->{$this->mode};
			return true;
		}		
		$this->error = (object)array(
			'code' => $result->getCode(),
			'text' => $result->getError(),
		);
		return false;
    }
}
