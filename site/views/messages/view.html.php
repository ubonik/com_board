<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Factory;

class BoardViewMessages extends HtmlView
{
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

    protected $params;

    /**
     * Display the view.
     *
     * @param string $tpl The name of the template file to parse.
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     *
     * @throws  Exception
     *
     * @since  2.0.0
     */
    public function display($tpl = null)
    {
        $items = $this->get('items');
        $this->pagination = $this->get('pagination');

        $this->state = $this->get('state');
        $this->params = Factory::getApplication()->getParams();

        if(is_array($items)) {
            foreach($items as $item) {
                $item->images = json_decode($item->images);
            }
        }

        $this->items = $items;

        return parent::display($tpl);
    }
}