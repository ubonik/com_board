<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

require_once JPATH_ADMINISTRATOR . '/components/com_board/models/messages.php';

class BoardModelUserMessages extends BoardModelMessages
{
    protected function populateState($ordering = null, $direction = null)
    {
        $this->setState('filter.author', Factory::getUser()->id);

        parent::populateState('id', 'DESC');
    }
}
