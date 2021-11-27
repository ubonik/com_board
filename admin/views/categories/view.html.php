<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;
use  Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Object\CMSObject;

class BoardViewCategories extends HtmlView
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
     * Name of the sorted field
     *
     * @var string
     *
     * @since  2.0.0
     */
    //хранится название поля по которому идет сортировка
    protected $listOrder;
    /**
     * Sorting direction
     * @var string
     *
     * @since  2.0.0
     */
    //хранится направление сортировки
    protected $listDirn;
    /**
     * If the sorting is by the ordering field, then true
     *
     * @var bool
     *
     * @since  2.0.0
     */
    //true, если сортировка по полю ordering
    protected $saveOrder;
    /**
     * Rebuilding the item array
     *
     * @var array
     *
     * @since  2.0.0
     */
    protected $categories;
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
        ToolbarHelper::title(Text::_('COM_BOARD') . ': ' . Text::_('COM_BOARD_CATEGORIES'));

        // Add create button
        ToolbarHelper::addNew('category.add');

        // Add edit button
        ToolbarHelper::editList('category.edit');

        // Add publish & unpublish buttons
        ToolbarHelper::publish('categories.publish', 'JTOOLBAR_PUBLISH', true);
        ToolbarHelper::unpublish('categories.unpublish', 'JTOOLBAR_UNPUBLISH', true);

        // Add delete button
        ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'categories.delete');

        // Add preferences button
        ToolbarHelper::preferences('com_board');
    }
}
