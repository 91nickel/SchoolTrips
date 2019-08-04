<?php
/**
 * amoCRM класс
 * Работа с временными файлами
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class Tempfile
{
    private static $_data = array();

    /**
     * Сохранение в файл
     */
    public static function write($key, $data)
    {
        if (file_put_contents(AMOTEMP . '/' . $key . '.amotemp', serialize($data))) {
            self::$_data[$key] = $data;
            return $this->read($key);
        }
        return null;
    }

    /**
     * Чтение из файла
     */
    public static function read($key, $default = false)
    {
        if (isset(self::$_data[$key])) {
            return self::$_data[$key];
        }
        if (file_exists(AMOTEMP . '/' . $key . '.amotemp')) {
            return unserialize(file_get_contents(AMOTEMP . '/' . $key . '.amotemp'));
        }
        if ($default !== false) {
            return $default;
        }
        return null;
    }
}
