<?php

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Router\Route;

class BoardControllerMessage extends FormController
{
    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    protected function allowAdd($data = array()) {
        $user = Factory::getUser();
        return ($user->authorise('core.create', $this->option) || $user->authorise('core.create.messages', $this->option));
    }

    /**
     * Overriding the method to check whether the user can edit an existing record.
     *
     * @param   array   $data  Data array.
     * @param   string  $key   Primary key name.
     *
     * @return  boolean  True if the record is allowed to be edited.
     * @since 2.0
     */
    protected function allowEdit($data = array(), $key = 'id')
    {
        $recordId = (int) isset($data[$key]) ? $data[$key] : 0;

        if ($recordId)
        {
            //var_dump(Factory::getUser()->authorise( "core.edit", "com_doska.message.6"));
            //exit();
            // Checking editing at the record level.
            return (Factory::getUser()->authorise('core.edit', $this->option . '.message.' . $recordId)
                || Factory::getUser()->authorise('core.edit.own', $this->option . '.message.' . $recordId));
        }
        else
        {
            // Checking the editing at the component level.
            return parent::allowEdit($data, $key);
        }
    }

    public function getModel($name = 'form', $prefix = '', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }

    public function save($key = null, $urlVar = null)	{

        if(parent::save($key,$urlVar)) {

            $menu		= Factory::getApplication()->getMenu('site');
            $component  = ComponentHelper::getComponent($this->option);

            $attributes = array('component_id');
            $values     = array($component->id);

            $items = $menu->getItems($attributes, $values);

            if(!empty($items) && is_array($items)) {
                foreach($items as $item) {
                    if (isset($item->query) && isset($item->query['view']))	{
                        if($item->query['view'] == 'usermessages') {
                            $this->setRedirect(
                                Route::_(
                                    'index.php?option=' . $this->option.'&Itemid='.$item->id)
                            );
                            return TRUE;
                        }
                    }
                }
            }
        }

        $this->setRedirect('index.php');
        return TRUE;
    }

    public function cancel($key = null)	{
        if(parent::cancel($key)) {
            $menu		= Factory::getApplication()->getMenu('site');
            $component  = ComponentHelper::getComponent($this->option);

            $attributes = array('component_id');
            $values     = array($component->id);

            $items = $menu->getItems($attributes, $values);

            if(!empty($items) && is_array($items)) {
                foreach($items as $item) {
                    if (isset($item->query) && isset($item->query['view']))	{
                        if($item->query['view'] == 'usermessages') {
                            $this->setRedirect(
                                JRoute::_(
                                    'index.php?option=' . $this->option.'&Itemid='.$item->id)
                            );
                            return TRUE;
                        }
                    }
                }
            }
        }
        $this->setRedirect('index.php');
        return TRUE;
    }
}