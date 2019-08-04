<?php
/**
 * Буфер данных
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class Buffer
{
	private static $_contents = [];
	
    /**
     * Has bufer value 
	 * @param string $key
	 * @return bool
     */
    public static function has($key)
    {
		return array_key_exists($key, self::$_contents);
    }
	
    /**
     * Set bufer value 
	 * @param string $key
	 * @param mixed $content
	 * @return mixed
     */
    public static function set($key, $content)
    {
        self::$_contents[$key] = $content;
		return self::$_contents[$key];
    }

    /**
     * Get buffer value
	 * @param string $key
	 * @return mixed
     */
    public static function get($key)
    {
		if (!array_key_exists($key, self::$_contents)) {
			return null;
		}
        return self::$_contents[$key];
    }
	
    /**
     * Remove buffer value
	 * @param string $key
	 * @return bool
     */
    public static function remove($key)
    {
		if (array_key_exists($key, self::$_contents)) {
			unset(self::$_contents[$key]);
			return true;
		}
		return false;
    }
}