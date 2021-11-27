<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Form\Form;

class BoardViewMessages extends HtmlView
{
    /**
     * View sidebar.
     *
     * @var  string
     *
     * @since  2.0.0
     */
    protected $sidebar;
    /**
     * Pagination object.
     *
     * @var  Pagination
     *
     * @since  2.0.0
     */
    protected $pagination;
    /**
     * Model state variables.
     *
     * @var  CMSObject
     *
     * @since  2.0.0
     */
    protected $state;
    /**
     * An array of items.
     *
     * @var  array
     *
     * @since  2.0.0
     */
    protected $items;
    /**
     * Form object for search filters.
     *
     * @var  Form
     *
     * @since  2.0.0
     */
    public $filterForm;
    /**
     * The active search filters.
     *
     * @var  array
     *
     * @since  2.0.0
     */
    public $activeFilters;
    /**
     * Name of the sorted field
     *
     * @var string
     *
     * @since  2.0.0
     */
    protected $listOrder;
    /**
     * True if sorting by the ordering field
     *
     * @var bool
     *
     * @since  2.0.0
     */
    protected $saveOrder;
    /**
     * Access verification
     *
     * @var CMSObject
     *
     * @since  2.0.0
     */
    public $canDo;
    /**
     * Sorting direction
     * @var string
     *
     * @since  2.0.0
     */
    public $listDirn;
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
        $this->sidebar = BoardHelper::addSubMenu('messages');

        $this->pagination = $this->get('pagination');
        $this->items = $this->get('items');

        $this->state = $this->get('state');
        $this->listOrder = $this->state->get('list.ordering');
        $this->listDirn = $this->state->get('list.direction');
        $this->saveOrder = ($this->listOrder == 'ordering');

        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

        $this->canDo = BoardHelper::getActions();
        // Add title and toolbar
        $this->addToolbar();

        return parent::display($tpl);
    }

    /**
     * Add title and toolbar.
     *
     * @since  2.0.0
     */
    protected function addToolbar()
    {
        // Set page title
        ToolbarHelper::title(Text::_('COM_BOARD_MESSAGES'));

        // Add create button
        if ($this->canDo->get('core.create.messages') || $this->canDo->get('core.create')) {
            ToolbarHelper::addNew('message.add');
        }

        // Add edit button
        if ($this->canDo->get('core.edit') || $this->canDo->get('core.edit.own')) {
            ToolbarHelper::editList('message.edit');
        }

        // Add publish & unpublish buttons
        if ($this->canDo->get('core.edit.state') || $this->canDo->get('core.edit.state.own')) {
            ToolbarHelper::unpublish('messages.unpublish');
            ToolbarHelper::publish('messages.publish');
        }

        // Add delete button
        if ($this->canDo->get('core.delete')) {
            ToolbarHelper::deleteList('JTOOLBAR_DELETE', 'messages.delete');
        }

        // Add preferences button
        if ($this->canDo->get('core.admin')) {
            ToolbarHelper::preferences('com_board');
        }
    }
}
