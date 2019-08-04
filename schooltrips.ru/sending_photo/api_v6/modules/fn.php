<?php
/**
 * amoCRM класс
 * Функции для работы
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class Fn
{
    /**
     * Получение месяца на русском
     */
    public static function monthRu($date)
    {
        $a = array('/January/', '/February/', '/March/', '/April/', '/May/', '/June/', '/July/', '/August/', '/September/', '/October/', '/November/', '/December/');
        $b = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');

        $date = preg_replace($a, $b, $date);
        return $date;
    }
}
