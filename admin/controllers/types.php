<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\AdminController;

class BoardControllerTypes extends AdminController
{
    protected $text_prefix = 'COM_BOARD_TYPES';

    public function getModel($name = 'Type', $prefix = 'BoardModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }
}