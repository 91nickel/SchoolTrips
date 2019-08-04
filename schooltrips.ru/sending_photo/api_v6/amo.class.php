<?php
/**
 * Класс для работы с amoCRM
 * @author Vlad Ionov <vlad@f5.com.ru>
 * @version 6.1
 */
namespace Amo;

class CRM
{
	public $authorized = false;
    protected static $_query;
    private static $_instance = [];
    protected $auth,
			  $config = [
				'account' => null,
			  ],
			  $error = '',
			  $mod = [],
			  $cache_time = 1800,
			  $cache_file = '',
			  $services = [
				'leads' => 'leads',
				'contacts' => 'contacts',
				'company' => 'company',
				'customers' => 'customers',
				'customersPeriods' => 'customers_periods',
				'transactions' => 'transactions',
				'catalogs' => 'catalogs',
				'catalogElements' => 'catalog_elements',
				'links' => 'links',
				'notes' => 'notes',
				'tasks' => 'tasks',
				'webhooks' => 'webhooks',
				'users' => 'users',
				'custom' => 'custom',
				'leadinfo' => 'leadinfo',
				'cfields' => 'cfields'
			  ];
    /**
     * Constructor
     */
    private function __construct(Array $account)
    {
        self::$_query = new Query();
    }
	
    /**
     * Получим объект CRM
     */
    public static function instance(Array $account = [])
    {
        if (empty($account)) {
            throw new \Exception('Invalid amoCRM account');
        }
		if (empty($account['zone'])) {
			$account['zone'] = 'ru';
		}
		$auth = [
			'domain' => '', 'login' => '', 'hash' => '', 'zone' => ''
		];
		foreach ($auth as $key=>$val) {
			if (!isset($account[$key])) {
				throw new \Exception('Invalid auth field: '.$key);
			}
			$auth[$key] = $account[$key];
		}
		$key = md5(json_encode($auth));
        if (!isset(self::$_instance[$key])) {
            self::$_instance[$key] = new static($account);
			self::$_instance[$key]->auth = $auth;
			self::$_instance[$key]->cache_file = AMOTEMP.'/'.$auth['domain'].'_current.cache';
        }
		self::$_query->zone = $auth['zone'];
        return self::$_instance[$key];
    }
	
    /**
     * Получение авторизации аккаунта
     */
    public function getAuth()
    {
		return (object)$this->auth;
	}
	
    /**
     * Получение данных аккаунта
     */
    public function getCurrent()
    {
		if (!$this->authorized) {
			return $this->initAccount();
		}
		return Buffer::get($this->auth['domain']);
	}

    /**
     * Получение данных аккаунта
     */
    private function initAccount()
    {
        if ($account = $this->currentAccountCacheGet()) {
            $this->authorized = true;
            return Buffer::set($this->auth['domain'], $account);
		}
        $data = $this->query('get', '/private/api/v2/json/accounts/current');
        $resp = $data->getResp();
        if (!isset($resp->account)) {
			throw new \Exception('AmoCRM account unauthorized: '.$this->auth['domain'].'. Code: '.$data->getData()->response->error_code.'. Message: '.$data->getData()->response->error);
            return false;
        } else {
            $this->authorized = true;
			$this->currentAccountCacheSet($resp->account);
            return Buffer::set($this->auth['domain'], $resp->account);
        }
    }
	
    /**
     * Получение данных аккаунта из кеша
     */
    private function currentAccountCacheGet()
    {
		if ($this->cache_time < 1) {
			return null;
		}
		if (file_exists($this->cache_file)) {
			if (time()-filemtime($this->cache_file) > $this->cache_time) {
				return null;
			}
			if ($data = unserialize(file_get_contents($this->cache_file))) {
				return $data;
			}
		}
		return null;
	}
	
    /**
     * Запись данных аккаунта в кеш
     */
    private function currentAccountCacheSet($data)
    {
		if ($this->cache_time < 1) {
			return false;
		}
		if (!file_put_contents($this->cache_file, serialize($data), LOCK_EX)) {
			throw new \Exception('Error current account cache set to: '.$this->cache_file);
		}
		@chmod($this->cache_file, 0666);
	}

    /**
     * Запросы к серверу
     */
    public function query($type, $url, $args = false, $from = false)
    {
        return self::$_query->$type((object)$this->auth, $url, $args, $from);
    }
	
    /**
     * Время жизни кеша данных аккаунта
     */
    public function accountCacheTime($val)
    {
        $this->cache_time = $val;
    }

    /**
     * Включение логов для отладки
     */
    public function logs($enabled)
    {
        self::$_query->logs = $enabled;
    }
	
    /**
     * Работа со сделками
     */
    public function leads()
    {
		return new EntityService('leads', $this);
	}
	
    /**
     * Работа с контактами
     */
    public function contacts()
    {
		return new EntityService('contacts', $this);
	}
	
    /**
     * Работа с компаниями
     */
    public function company()
    {
		return new EntityService('company', $this);
	}
	
    /**
     * Работа с покупателями
     */
    public function customers()
    {
		return new EntityService('customers', $this);
	}
	
    /**
     * Работа с периодами покупателей
     */
    public function customers_periods()
    {
		return new EntityService('customers_periods', $this);
	}
	
    /**
     * Работа с покупками
     */
    public function transactions()
    {
		return new EntityService('transactions', $this);
	}
	
    /**
     * Работа с каталогами
     */
    public function catalogs()
    {
		return new EntityService('catalogs', $this);
	}
	
    /**
     * Работа с элементами каталогов
     */
    public function catalog_elements()
    {
		return new EntityService('catalog_elements', $this);
	}
	
    /**
     * Работа со связами каталогов
     */
    public function links()
    {
		return new EntityService('links', $this);
	}
	
    /**
     * Работа с примечаниями
     */
    public function notes()
    {
		return new EntityService('notes', $this);
	}
	
    /**
     * Работа с задачами
     */
    public function tasks()
    {
		return new EntityService('tasks', $this);
	}
	
    /**
     * Работа с веб-хуками
     */
    public function webhooks()
    {
		return new EntityService('webhooks', $this);
	}
	
    /**
     * Работа с пользователями
     */
    public function users()
    {
		if (!isset($this->mod['users'])) {
			$this->mod['users'] = new Userinfo($this);
		}
		return $this->mod['users'];
	}
	
    /**
     * Работа с доп.полями
     */
    public function custom()
    {
		if (!isset($this->mod['custom'])) {
			$this->mod['custom'] = new Custom($this);
		}
		return $this->mod['custom'];
	}
	
    /**
     * Работа с воронками
     */
    public function leadinfo()
    {
		if (!isset($this->mod['leadinfo'])) {
			$this->mod['leadinfo'] = new Leadinfo($this);
		}
		return $this->mod['leadinfo'];
	}
	
    /**
     * Работа с доп.полями
     */
    public function cfields()
    {
		if (!isset($this->mod['cfields'])) {
			$this->mod['cfields'] = new Cfields($this);
		}
		return $this->mod['cfields'];
	}
	
    /**
     * Получение сервиса
	 * @param string $field
     */
	public function __get($service)
	{
		if (!array_key_exists($service, $this->services)) {
			throw new \Exception('Invalid service called: '.$service);
		}
		$methodService = $this->services[$service];
		return $this->$methodService();
	}
	
    /**
     * Обработка кодов ошибок
     */
    protected function error($key, $text = '')
    {
        $errors = array(
            999 => 'Module is not exists',
            1000 => 'Unauthorized',
            1001 => 'Error load data',
            1002 => 'Empty set data',
            1003 => 'Invalid custom field',
            1004 => 'Error set'
        );
        $this->error = $errors[$key] . ' ' . $text;
    }
	
    /**
     * Показ ошибок
     */
    public function getError()
    {
        return $this->error;
    }
	
    /**
     * Destructor
     */
    public function __destruct()
    {
        //echo 'Memory used: '.(memory_get_peak_usage(true)/1024/1024)." MiB\n\n";
    }
}
