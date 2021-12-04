<?php
defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_board/controllers/messages.php';

class BoardControllerUsermessages extends BoardControllerMessages
{
    protected $view_list = 'usermessages';

    public function getModel($name = 'Usermessage', $prefix = 'BoardModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }
}

