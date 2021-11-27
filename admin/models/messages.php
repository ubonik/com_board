<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Factory;

class BoardModelMessages extends ListModel
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'id',
                'title',
                'author_name',
                'category',
                'type',
                'town',
                'price',
                'state',
                'confirm'
            );
        }

        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $user = Factory::getUser();

        // Select the required fields from the table.
        $query->select('p.id, title, text, id_user, town, images, confirm, price, created, publish_up, publish_down, hits, metadesc, metakey, modified, p.alias, p.state');
        $query->from('#__board_post AS p');

        $query->select('c.name as category');
        $query->join('LEFT','#__board_categories AS c ON c.id=p.id_categories');

        $query->select('t.name as type');
        $query->join('LEFT','#__board_types AS t ON t.id=p.id_types');

        $query->select('u.name AS author_name');
        $query->join('LEFT', '#__users AS u ON u.id=p.id_user');

        $search = $this->getState('filter.search');

        if (!empty($search))
        {
            $like = $db->quote('%' . $search . '%');
            $query->where('title LIKE ' . $like);
        }

        $category = $this->getState('filter.category');
        if(!empty($category)) {
            $query->where('p.id_categories = '.(int)$category);
        }

        $confirm = $this->getState('filter.confirm');
        if(is_numeric($confirm)) {
            $query->where('p.confirm = "'.(int)$confirm.'"');
        }

        $type = $this->getState('filter.type');
        if(!empty($type)) {
            $query->where('p.id_types = '.(int)$type);
        }

        $town = $this->getState('filter.town');
        if(!empty($town)) {
            $query->where('p.town ='.'"'.$town.'"');
        }

        $author = $this->getState('filter.author');
        if(!empty($author)) {
            $query->where('p.id_user = '.(int)$author);
        }

        // Фильтруем по состоянию.
        $published = $this->getState('filter.state');

        if (is_numeric($published))
        {
            $query->where('p.state = ' . (int) $published);
        }
        elseif (!$published)
        {
            $query->where('(p.state = 0 OR p.state = 1)');
        }

        $orderCol  = $db->escape($this->state->get('list.ordering', 'id'));
        $orderDirn = $db->escape($this->state->get('list.direction', 'asc'));
        $query->order($orderCol . ' ' . $orderDirn);

        return $query;
    }

    protected function populateState($ordering = null, $direction = null)
    {
        parent::populateState('id', 'desc');
    }

    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.category');
        $id .= ':' . $this->getState('filter.state');
        $id .= ':' . $this->getState('filter.type');
        $id .= ':' . $this->getState('filter.author');
        $id .= ':' . $this->getState('filter.town');
        $id .= ':' . $this->getState('filter.confirm');

        return parent::getStoreId($id);
    }
}