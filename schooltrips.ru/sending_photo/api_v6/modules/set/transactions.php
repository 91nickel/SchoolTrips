<?php
/**
 * Добавление покупок
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class TransactionsSet extends EntitySet
{
    public function __construct(\Amo\CRM $instance)
    {
		parent::__construct('transactions', $instance);
	}
	
    /**
     * Текст комментария
     */
    public function comment($value)
    {
        if (!empty($value)) $this->setValue('comment', $value);
        return $this;
    }

    /**
     * ID привязываемого покупателя
     */
    public function customerId($value)
    {
        if (!empty($value)) $this->setValue('customer_id', $value);
        return $this;
    }

    /**
     * Дата покупки
     */
    public function date($value)
    {
        if (!empty($value)) $this->setValue('date', $value);
        return $this;
    }

    /**
     * Сумма покупки
     */
    public function price($value)
    {
        if (!empty($value)) $this->setValue('price', $value);
		return $this;
    }

    /**
     * Дата следующей покупки
     */
    public function nextDate($value)
    {
        if (!empty($value)) $this->setValue('next_date', $value);
        return $this;
    }

    /**
     * Сумма следующей покупки
     */
    public function nextPrice($value)
    {
        if (!empty($value)) $this->setValue('next_price', $value);
		return $this;
    }
}
