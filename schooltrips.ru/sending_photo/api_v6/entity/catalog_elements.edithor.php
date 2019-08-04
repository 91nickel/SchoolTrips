<?php
/**
 * Класс редактирования элементов каталогов
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class Catalog_elementsEntityCfEdithor extends EntityCfEdithor
{
    /**
     * Write RAW data
	 * @param $key string field name
	 * @param $val mixed field val
	 * @return EntityEdithor
     */
    public function field($key, $val)
    {
		if ($key == 'catalog_id') {
			$this->cfinit($val);
		}
		return parent::field($key, $val);
	}
}
