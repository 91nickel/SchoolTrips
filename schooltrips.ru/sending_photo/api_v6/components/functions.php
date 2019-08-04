<?php
/**
 * Вспомогательные функции
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

function api($account)
{
	return \Amo\CRM::instance($account);
} 

/**
 * Месяц на русском
 */
function monthRU($date, $type = 0)
{
    $a = array('/January/', '/February/', '/March/', '/April/', '/May/', '/June/', '/July/', '/August/', '/September/', '/October/', '/November/', '/December/');
    $b = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
    if ($type == 1) {
        $b = array('Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');
    }
    $date = preg_replace($a, $b, $date);
    return $date;
}

/**
 * Json encode
 */
function jsonEncode($val)
{
    return json_encode($val, JSON_UNESCAPED_UNICODE);
}
