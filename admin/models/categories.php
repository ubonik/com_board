<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Factory;

class BoardModelCategories extends ListModel
{
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
echo $query;
        return $query;
    }

    protected function populateState($ordering = null, $direction = null)
    {
      //  $state = $this->getUserStateFromRequest();
        parent::populateState('id', 'desc');
    }
}