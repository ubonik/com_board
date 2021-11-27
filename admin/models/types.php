<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Factory;

class BoardModelTypes extends ListModel
{
    /**
     * Method to get a JDatabaseQuery object for retrieving the data set from a database.
     *
     * @return  JDatabaseQuery  A JDatabaseQuery object to retrieve the data set.
     *
     * @since  2.0.0
     */
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
