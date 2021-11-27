<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Factory;

class BoardModelCategories extends ListModel
{
    /**
     * Constructor.
     *
     * @param   array  $config  An optional associative array of configuration settings.
     *
     * @since  2.0.0
     */
    public function __construct($config = [])
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = [
                'id',
                'name',
                'state',
                'ordering'
            ];
        }

        parent::__construct($config);
    }

    /**
     * Method to get a JDatabaseQuery object for retrieving the data set from a database.
     *
     * @return  JDatabaseQuery  A JDatabaseQuery object to retrieve the data set.
     *
     * @since  2.0.0
     */
    protected function getListQuery()
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id, name, parentid, alias, state, ordering')
        ->from('#__board_categories')
        ;
        $orderCol = $db->escape($this->getState('list.ordering', 'id'));
        $orderDirn = $db->escape($this->getState('list.direction', 'desc'));

        $query->order($orderCol . ' ' . $orderDirn);

        return $query;
    }

    /**
     * Method to auto-populate the model state.
     *
     * @param   string  $ordering   An optional ordering field.
     * @param   string  $direction  An optional direction (asc|desc).
     *
     * @since  2.0.0
     */
    protected function populateState($ordering = null, $direction = null)
    {
        parent::populateState('id', 'desc');
    }
}