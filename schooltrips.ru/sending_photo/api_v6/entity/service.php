<?php
/**
 * Entity modules service
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class EntityService
{
	protected $_instance,
			  $entity;
	protected static
			  $_modules = [
				'Amo\\LinksList',
				'Amo\\LinksSet'
			  ];
			  
    public function __construct($entity,\Amo\CRM $instance)
    {
		$this->_instance = $instance;
		$this->entity = $entity;
	}
	
    /**
     * Entity get mode
     */
    public function get()
    {
		$module = $this->loadModule('get');
		return new $module($this->_instance);
    }
	
    /**
     * Entity set mode
     */
    public function set()
    {
		$module = $this->loadModule('set');
		return new $module($this->_instance);
    }
	
    /**
     * Entity create mode
     */
    public function create($entitys = [])
    {
		$module = $this->loadModule('create');
		$creathor = new $module($this->entity, $this->_instance);		
		if (!empty($entitys)) {
			if (!is_array($entitys)) {
				$entitys = [$entitys];
			}
			return $creathor->create($entitys);
		}
		return $creathor->entity();
    }
	
    /**
     * Entity update mode
     */
    public function update()
    {
		$module = $this->loadModule('update');
		return new $module($this->_instance);
    }
	
    /**
     * Entity save mode
     */
    public function save($entitys = [])
    {
		$module = $this->loadModule('save');
		$saver = new $module($this->entity, $this->_instance);		
		if (!empty($entitys)) {
			if (!is_array($entitys)) {
				$entitys = [$entitys];
			}
			return $saver->update($entitys);
		}
    }
	
    /**
     * Entity delete mode
     */
    public function delete($entitys = [])
    {
		$module = $this->loadModule('delete');
		$deleter = new $module($this->entity, $this->_instance);		
		if (!empty($entitys)) {
			if (!is_array($entitys)) {
				$entitys = [$entitys];
			}
			return $deleter->delete($entitys);
		}
    }
	
    /**
     * Entity links mode
     */
    public function links()
    {
		$module = $this->loadModule('links');
		return new $module($this->_instance);
    }
	
    /**
     * Link list mode
     */
    public function lists()
    {
		$module = $this->loadModule('list');
		return new $module($this->_instance);
    }
	
    /**
     * Entity unsubscribe mode
     */
    public function unsubscribe()
    {
		$module = $this->loadModule('unsubscribe');
		return new $module($this->_instance);
    }
	
    /**
     * Entity remove mode
     */
    public function remove($entity)
    {
		$module = $this->loadModule('remove');
		return new $module($entity, $this->_instance);
    }
	
    /**
     * Load module
     */
    private function loadModule($mode)
    {
		$classname = 'Amo\\'.ucfirst($this->entity).ucfirst($mode);
		$module = $mode.$this->entity;
		if (!in_array($classname, static::$_modules)) {
			if (!file_exists(AMODULES.'/'.$mode.'/'.$this->entity.'.php')) {
				throw new \Exception('Invalid module: '.$classname.' '.AMODULES.'/'.$mode.'/'.$this->entity.'.php');
			}
			require_once(AMODULES.'/'.$mode.'/'.$this->entity.'.php');
			static::$_modules[]= $module;
		}
		return $classname;
	}
}