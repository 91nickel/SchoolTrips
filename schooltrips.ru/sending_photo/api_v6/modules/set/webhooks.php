<?php
/**
 * Добавление веб-хуков
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class WebhooksSet extends EntitySet
{
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct('webhooks', $instance);
		$this->setAction('subscribe');
		$this->setMode('subscribe');
	}
	
    /**
     * URL оповещения
     */
    public function url($value)
    {
        if (!empty($value)) $this->setValue('url', $value);
        return $this;
    }

    /**
     * Список событий
     */
    public function events($values)
    {
        if (!is_array($values)) {
            $values = array($values);
        }
        $this->setValue('events', $values);
        return $this;
    }
}