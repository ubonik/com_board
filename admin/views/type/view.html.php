<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

class boardViewType extends HtmlView
{
    protected $form;
    protected $item;

    public function display($tpl = null)
    {
        $this->form = $this->get('form');
        $this->item = $this->get('item');
        $this->addToolbar();
        return parent::display($tpl);
    }

    public function addToolbar()
    {
        // Factory::getApplication()->input->set('hidemainmenu', true);
        $isNew = ($this->item->id == 0);
        $title = $isNew ? Text::_('COM_BOARD_TYPE_ADD') : Text::_('COM_BOARD_TYPE_EDIT');
        ToolbarHelper::title(Text::_('COM_BOARD') . ': ' . $title);
        ToolbarHelper::apply('type.apply');
        ToolbarHelper::save('type.save');
        ToolbarHelper::cancel('type.cancel', 'JTOOLBAR_CLOSE');
    }


}