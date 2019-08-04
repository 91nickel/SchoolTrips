<?php
/**
 * amoCRM класс
 * Получение информации о сделках
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 * @version 1.00
 */
namespace Amo;

class Leadinfo
{
    private $_instance,
			$pipelines,
			$status_from_id = array(),
			$pipelines_from_id;

    public function __construct(\Amo\CRM $instance)
	{
		$this->_instance = $instance;
		$current = $this->_instance->getCurrent();

        foreach ($current->leads_statuses as $k => $status) {

            $status->pos = $k + 1;
            $this->status_from_id[$status->id] = $status;
        }
		$this->pipelines = (object)[];
		$this->pipelines_from_id = (object)[];
		
        if (!empty($current->pipelines)) {

			foreach ($current->pipelines as $id=>&$pipeline) {
				
				$statuses = (array)$pipeline->statuses;
				
				usort($statuses, function($a, $b) {
					return $a->sort < $b->sort ? 1 : -1;
				});
				$this->pipelines->{$id} = $pipeline;
				$this->pipelines_from_id->{$id} = $pipeline;
			}
            foreach ($this->pipelines_from_id as $pipeline) {

                foreach ((array)$pipeline->statuses as $status) {

                    if (!isset($this->status_from_id[$status->id])) {

                        $this->status_from_id[$status->id] = $status;
                    }
                }
            }
        }
    }

    /**
     * Получение воронок
     */
    public function pipelines()
    {
        return $this->pipelines;
    }
	
    /**
     * Получение статусов
     */
    public function statusList()
    {
        return $this->status_from_id;
    }

    /**
     * Получение имени статуса по id
     */
    public function statusFromId($status_id, $pipeline_id = 0)
    {
        if ($pipeline = $this->pipelineFromId($pipeline_id)) {

            return $pipeline->statuses->{$status_id};
        }
        if (isset($this->status_from_id[$status_id])) {

            return $this->status_from_id[$status_id];
        }
        return null;
    }

    /**
     * Получение имени воронки по id
     */
    public function pipelineFromId($pipeline_id)
    {
        if (is_numeric($pipeline_id) && $pipeline_id > 0) {

            if (isset($this->pipelines_from_id->{$pipeline_id})) {

                return $this->pipelines_from_id->{$pipeline_id};
            }
        }
        return null;
    }
	
    /**
     * Получение следующего статуса воронки
     */
    public function statusNextFrom($pipeline_id, $status_id)
    {
		if (in_array($status_id, [142, 143])) {
			
			return $this->statusFromId($status_id, $pipeline_id);
		}
        if ($pipeline = $this->pipelineFromId($pipeline_id)) {

            $currStatus =  $pipeline->statuses->{$status_id};
			
			foreach ($pipeline->statuses as $status) {
				
				if ($status->sort > $currStatus->sort) {
					
					return $status;
				}
			}
        }
		return false;
	}
}
