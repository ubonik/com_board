<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Factory;

class BoardViewCategory extends HtmlView
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
    private function addToolbar()
    {
        $isNew = ($this->item->id == 0);

        // Disable menu
        Factory::getApplication()->input->set('hidemainmenu', true);

        // Set page title
        $title = $isNew ? Text::_('COM_BOARD_CATEGORY_ADD') : Text::_('COM_BOARD_CATEGORY_EDIT');
        ToolbarHelper::title(Text::_('COM_BOARD') . ': ' . $title);

        // Add save new button
        ToolbarHelper::apply('category.apply');
        ToolbarHelper::save('category.save');

        // Add cancel button
        ToolbarHelper::cancel('category.cancel', 'JTOOLBAR_CLOSE');
    }
}