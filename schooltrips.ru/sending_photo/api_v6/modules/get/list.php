<?php
/**
 * ��������� ������ �� amoCRM
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class EntityList
{
	protected $_instance,
			  $entity,
			  $max = 0,
			  $search = array(
				'limit_rows' => 500,
				'limit_offset' => 0,
				'filter' => array()
			  ),
			  $error;
			  
    public function __construct(\Amo\CRM $instance)
    {
		$this->_instance = $instance;
	}
	
    /**
     * ��������� �������� ��� ������
     */
    public function entity($entity)
    {
		$this->entity = $entity;
		return $this;
    }
	
    /**
     * ��������� ��������� ������
     */
    public function param($key, $value)
    {
        $this->search[$key] = $value;
		return $this;
    }
	
    /**
     * ��������� ���������
     */
    public function setMax($value)
    {
        $this->max = $value;
		if ($this->search['limit_rows'] > $this->max) {
			$this->search['limit_rows'] = $this->max;
		}
        return $this;
    }
	
    /**
     * ������ ���������
     */
    public function filter($key, $val)
    {
		if (empty($key)) {
			return $this;
		}
		$this->search['filter'][$key] = $val;
		return $this;
	}
	
    /**
     * ��������� ���������
     */
    public function run()
    {
		$i = $this->search['limit_rows'];
		$geted = array();
		
		while ($i >= $this->search['limit_rows']) {

			$data = $this->get();
			if ($data === false) {
				return false;
			}
			if (empty($data)) {
				break;
			}
			$count = count($data);
			$i = $count;

			if ($i > 0) {
				foreach ($data as $entity) {
					$geted[] = $entity;
				}
			}
			if ($this->search['limit_rows'] == $i && $this->search['limit_rows'] < 500) {
				break;
			} else {
				$this->search['limit_offset'] += $i;
			}
			if ($this->max > 0 && count($geted) >= $this->max) {
				break;
			}
		}
		return $geted;
	}
	
    /**
     * ������ ��� ��������� �����
     */
    protected function get()
    {
        $data = $this->_instance->query('get', '/private/api/v2/json/' . $this->entity . '/list', $this->search)->getData();
		if (!empty($data->response->error)) {
			$this->error = $data->response->error;
			return false;
		}
        if (!isset($data->response->{$this->entity})) {
            return array();
        }
		return $data->response->{$this->entity};
    }
	
    /**
     * ����� ������
     */
    public function getError()
    {
		return $this->error;
	}
}