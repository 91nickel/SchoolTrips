<?php
/**
 * Получение связей между контактами и сделками
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

class LeadsLinks extends EntityLinks
{
    /**
     * Получение сделок по контактам
     */
    public function from($values)
    {
        if (!is_array($values)) {
            $values = array($values);
        }
        $this->setParam('contacts_link', $values);
        if ($data = $this->run()) {

            if (isset($data[0])) {

                $geted = array();
                foreach ($data as $item) {

                    $geted[$item->contact_id][] = $item->lead_id;
                }
				if (count($values) == 1) {
					return $geted[$values[0]];
				}
                return $geted;
            }
        }
        return array();
    }
}
