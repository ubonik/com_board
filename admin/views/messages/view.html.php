<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;

class BoardViewMessages extends HtmlView
{
    protected $sidebar;
    protected $pagination;
    protected $items;
    protected $listOrder;
    protected $saveOrder;
    public $filterForm;
    public $listDirn;
    public $activeFilters;
    public $canDo;

    public function display($tpl = null)
    {
        $this->sidebar = BoardHelper::addSubMenu('messages');

        $this->pagination = $this->get('pagination');
        $this->items = $this->get('items');

        $state = $this->get('state');
        $this->listOrder = $state->get('list.ordering');
        $this->listDirn = $state->get('list.direction');
        $this->saveOrder = ($this->listOrder == 'ordering');

        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

        $this->canDo = BoardHelper::getActions();

        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        ToolbarHelper::title(Text::_('COM_BOARD_MESSAGES'));

        if ($this->canDo->get('core.create.messages') || $this->canDo->get('core.create')) {
            ToolbarHelper::addNew('message.add');
        }

        if ($this->canDo->get('core.edit') || $this->canDo->get('core.edit.own')) {
            ToolbarHelper::editList('message.edit');
        }

        if ($this->canDo->get('core.edit.state') || $this->canDo->get('core.edit.state.own')) {
            ToolbarHelper::unpublish('messages.unpublish');
            ToolbarHelper::publish('messages.publish');
        }

        if ($this->canDo->get('core.delete')) {
            ToolbarHelper::deleteList('JTOOLBAR_DELETE', 'messages.delete');
        }

        if ($this->canDo->get('core.admin')) {
            ToolbarHelper::preferences('com_board');
        }
    }
}
