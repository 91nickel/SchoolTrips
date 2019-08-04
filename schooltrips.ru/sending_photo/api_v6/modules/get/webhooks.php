<?php
/**
 * Получение списка веб-хуков
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class WebhooksGet extends EntityGet
{
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct($instance);
		$this->setKey('webhooks');
		$this->setEntity('webhooks');
	}
	
    /**
     * Получение по событиям
     */
    public function byEvents($events)
    {
		if (!is_array($events)) {
			$events = array($events);
		}
		$this->setParam('events', $events);
		if ($data = $this->run()) {
			return $data;
		}
		return null;
	}
	
    /**
     * Получение по URL
     */
    public function byUrl($url)
    {
		$this->setParam('url', $url);
		if ($data = $this->run()) {
			return $data;
		}
		return null;
	}
}
