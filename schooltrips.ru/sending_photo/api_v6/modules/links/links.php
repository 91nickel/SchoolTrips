<?php
/**
 * Получение из amoCRM
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class EntityLinks extends EntityGet
{
	protected $key = 'links',
			  $entity = 'contacts',
			  $typ = 'links';

    /**
     * Получение данных
     */
    public function run()
    {
		$i = $this->search['limit_rows'];
		$geted = array();
		while ($i >= $this->search['limit_rows']) {

			if (!$data = $this->getData()) break;
			$count = count($data->{$this->key});
			$i = $count;

			if ($i > 0) {
				foreach ($data->{$this->key} as $entity) {
					$geted[] = $entity;
				}
			}
			if ($this->search['limit_rows'] == $i && $this->search['limit_rows'] < 500) {
				break;
			} else {
				$this->search['limit_offset'] += $i;
			}
			if ($this->max > 0 && count($geted) >= $this->max) {
				break; // ограничим
			}
		}
		return $geted;
    }
	
    /**
     * Запрос для получения
     */
    protected function getData()
    {
        $data = $this->_instance->query('get', '/private/api/v2/json/' . $this->entity . '/' . $this->typ, $this->search, $this->time_from);
        if (is_object($data)) {
            return $data->getResp();
        }
        return false;
    }
}
