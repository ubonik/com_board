<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

class BoardControllerTypes extends AdminController
{
    /**
     * The prefix to use with controller messages.
     *
     * @var  string
     *
     * @since  2.0.0
     */
    protected $text_prefix = 'COM_BOARD_TYPES';

    /**
     * Proxy for getModel.
     *
     * @param   string  $name    The model name.
     * @param   string  $prefix  The class prefix.
     * @param   array   $config  The array of possible config values.
     *
     * @return  BaseDatabaseModel|BoardModelCategory  A model object.
     *
     * @since  2.0.0
     */
    public function getModel($name = 'Type', $prefix = 'BoardModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }
}