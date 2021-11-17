<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Factory;

class BoardModelTypes extends ListModel
{
    protected function getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(['id', 'name', 'alias', 'state']))
            ->from($db->quoteName('#__board_types'))
        ;

        return $query;
    }
}
