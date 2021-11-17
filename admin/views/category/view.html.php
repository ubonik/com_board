<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

class BoardViewCategory extends HtmlView
{
    protected $form;
    protected $item;
    //protected $state;

    public function display($tpl = null)
    {
        $this->form = $this->get('form');
        //var_dump($this->form);
        $this->item = $this->get('item');
        $this->addToolbar();
        return parent::display($tpl);
    }

    private function addToolbar()
    {
       // Factory::getApplication()->input->set('hidemainmenu', true);
        $isNew = ($this->item->id == 0);
        $title = $isNew ? Text::_('COM_BOARD_CATEGORY_ADD') : Text::_('COM_BOARD_CATEGORY_EDIT');
        ToolbarHelper::title(Text::_('COM_BOARD') . ': ' . $title);
        ToolbarHelper::apply('category.apply');
        ToolbarHelper::save('category.save');
        ToolbarHelper::cancel('category.cancel', 'JTOOLBAR_CLOSE');
    }
}