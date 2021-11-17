<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;
use  Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;


class BoardViewCategories extends HtmlView
{
    protected $items;
    protected $sidebar;
    protected $pagination;
    protected $state;
    //хранится название поля по которому идет сортировка
    protected $listOrder;
    //хранится направление сортировки
    protected $listDirn;
    //true, если сортировка по полю ordering
    protected $saveOrder;
    protected $categories;


    public function display($tpl = null)
    {
        $this->sidebar = BoardHelper::addSubMenu('categories');
        $categories = $this->get('items');
        $this->categories = $categories;
        $this->items = [];

        if (is_array($categories)) {
            foreach ($categories as $row) {
                if (!$row->parentid) {
                    $this->items[$row->id]['name'] = $row->name;
                    $this->items[$row->id]['state'] = $row->state;
                    $this->items[$row->id]['ordering'] = $row->ordering;
                } else {
                    $this->items[$row->parentid]['next'][] = [
                        'id' => $row->id,
                        'name' => $row->name,
                        'state' => $row->state,
                        'ordering' => $row->ordering
                    ];
                }
            }
        }

        $this->pagination = $this->get('pagination');
        $this->state = $this->get('state');

        $this->listOrder = $this->escape($this->state->get('list.ordering'));
        $this->listDirn = $this->escape($this->state->get('list.direction'));
        $this->saveOrder = ($this->listOrder == 'ordering');

        echo '<pre>';
        //print_r($this->items);
        echo '</pre>';
      //  var_dump($this->state);
        $this->addToolbar();

        parent::display($tpl);
    }

    public function addToolbar()
    {
        ToolbarHelper::title(Text::_('COM_BOARD') . ': ' . Text::_('COM_BOARD_CATEGORIES'));
        ToolbarHelper::addNew('category.add');
        ToolbarHelper::editList('category.edit');
        ToolbarHelper::publish('categories.publish', 'JTOOLBAR_PUBLISH', true);
        ToolbarHelper::unpublish('categories.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'categories.delete');
        ToolbarHelper::preferences('com_board');
    }
}
