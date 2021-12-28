<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;

class BoardModelMessage extends ItemModel
{
    protected function populateState()
    {
        //var_dump($this); exit;
        $app = Factory::getApplication();
        $id = $app->input->getInt('id');
        $this->setState('message.id', $id);
        $params = $app->getParams();
        $this->setState('params', $params);
    }

    public function getItem($pk = null)
    {
        $pk = (!empty($pk)) ?: $this->getState('message.id');

        if ($pk) {
            $db = $this->getDbo();
            $query = $db->getQuery(true);
            $query->select(
                'p.id, p.params, p.id_categories,p.id_types, title, introtext, `fulltext`, id_user, town, 
                images, price, created, publish_up, publish_down, hits, metadesc, metakey, modified, p.alias'
            );
            $query->from('#__board_post AS p');
            $query->select('c.name as category, c.alias AS catalias');
            $query->join('LEFT', '#__board_categories AS c ON c.id=p.id_categories');
            $query->select('t.name as type, t.alias AS typealias');
            $query->join('LEFT', '#__board_types AS t ON t.id=p.id_types');

            $query->select('u.name AS author_name')
                ->join('LEFT', '#__users AS u ON u.id=p.id_user');

            $query->where('p.id = ' . $pk);
            $query->where('p.state = 1');
            $query->where('p.confirm = "1"');
            $query->where('p.publish_up <= NOW()');
            $query->where('p.publish_DOWN >= NOW()');


            $db->setQuery($query);

            $data = $db->loadObject();

            if ($data) {
                $params = new JRegistry;
                $params->loadString($data->params);
                $data->params = $params;

                $params = clone $this->getState('params');
                $params->merge($data->params);

                $data->params = $params;

                return $data;
            }
        }
        return false;
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('message.id');
        $id .= ':' . $this->getState('params');

        return parent::getStoreId($id);
    }

    public function hit($pk = 0)
    {
        $pk = (!empty($pk)) ?: (int)$this->getState('message.id');
        $table = Table::getInstance('Message', 'BoardTable');

        if ($table->load($pk)) {
            $table->hit($pk);
        }
    }
}
