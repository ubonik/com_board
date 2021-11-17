<?php
defined('_JEXEC') or die;

use \Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;

class BoardViewTypes extends HtmlView
{
    protected $items;
    protected $sidebar;
    public function display($tpl = null)
    {
        $this->addToolbar();
        $this->items = $this->get('Items');
        $this->sidebar = BoardHelper::addSubMenu('types');
       // var_dump($this->items);
        return parent::display($tpl);
    }

    public function addToolbar()
    {
        ToolbarHelper::title(Text::_('COM_BOARD') . ': ' . Text::_('COM_BOARD_TYPES'));
        ToolbarHelper::addNew('type.add');
        ToolbarHelper::editList('type.edit');
        ToolbarHelper::publish('types.publish', 'JTOOLBAR_PUBLISH', true);
        ToolbarHelper::unpublish('types.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'types.delete');
        ToolbarHelper::preferences('com_board');
    }

}