<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Factory;

class BoardViewMessage extends HtmlView
{
    /**
     * Form object.
     *
     * @var  Form
     *
     * @since  2.0.0
     */
    protected $form;
    /**
     * The active item.
     *
     * @var  object
     *
     * @since  2.0.0
     */
    protected $item;
    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse.
     *
     * @throws  Exception
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     *
     * @since  2.0.0
     */
    public function display($tpl = null)
    {
        $this->form = $this->get('form');
        $this->item = $this->get('item');

        // Check for errors
        if (count($errors = $this->get('Errors')))
        {
            throw new Exception(implode('\n', $errors), 500);
        }

        // Add title and toolbar
        $this->addToolbar();

        return parent::display($tpl);
    }

    /**
     * Add title and toolbar.
     *
     * @throws  Exception
     *
     * @since  2.0.0
     */
    protected function addToolbar()
    {
        $isnew = ($this->item->id == 0);
        $canDo =  BoardHelper::getActions();

        // Disable menu
        Factory::getApplication()->input->set('hidemainmenu', true);

        // Set page title
        $title = $isnew ? Text::_('COM_BOARD_MESSAGE_EDIT') : Text::_('COM_BOARD_MESSAGE_ADD');
        ToolbarHelper::title($title);

        // Add save new button
        if ($canDo->get('core.edit')) {
            ToolbarHelper::apply('message.apply');
            ToolbarHelper::save('message.save');
        }

        // Add save new button
        if ($canDo->get('core.create')) {
            ToolbarHelper::save2new('message.save2new');
        }

        // Add cancel button
        ToolbarHelper::cancel('message.cancel');
    }
}