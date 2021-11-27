<?php
defined('_JEXEC') or die;

use \Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;

class BoardViewTypes extends HtmlView
{
    /**
     * An array of items.
     *
     * @var  array
     *
     * @since  2.0.0
     */
    protected $items;
    /**
     * View sidebar.
     *
     * @var  string
     *
     * @since  2.0.0
     */
    protected $sidebar;
    /**
     * Display the view.
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
        $this->items = $this->get('Items');
        $this->sidebar = BoardHelper::addSubMenu('types');

        // Add title and toolbar
        $this->addToolbar();

        return parent::display($tpl);
    }

    /**
     * Add title and toolbar.
     *
     * @since  2.0.0
     */
    public function addToolbar()
    {
        // Set page title
        ToolbarHelper::title(Text::_('COM_BOARD') . ': ' . Text::_('COM_BOARD_TYPES'));

        // Add create button
        ToolbarHelper::addNew('type.add');

        // Add edit button
        ToolbarHelper::editList('type.edit');

        // Add publish & unpublish buttons
        ToolbarHelper::publish('types.publish', 'JTOOLBAR_PUBLISH', true);
        ToolbarHelper::unpublish('types.unpublish', 'JTOOLBAR_UNPUBLISH', true);

        // Add delete button
        ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'types.delete');

        // Add preferences button
        ToolbarHelper::preferences('com_board');
    }

}