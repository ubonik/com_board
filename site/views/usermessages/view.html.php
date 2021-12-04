<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

class BoardViewUsermessages extends HtmlView
{
    protected $items;
    protected $state;
    protected $pagination;
    protected $params;
    public $listOrder;
    public $listDirn;

    public function display($tpl = null)
    {
        $items = $this->get('items');
        $this->state = $this->get('state');
       $this->pagination = $this->get('pagination');
        $this->params = Factory::getApplication()->getParams();

        $this->listOrder = $this->state->get('list.ordering');
        $this->listDirn = $this->state->get('list.direction');

        if ($items) {
            foreach ($items as $item) {
                $item->slug = $item->alias ? $item->id . ':' . $item->alias : $items->id;
            }

        }

        $this->items = $items;
        $this->canDo =Boardhelper::getActions();

        parent::display($tpl);
        $this->setDocument();
    }

    protected function setDocument()
    {
        $document = Factory::getDocument();
       // $document->addStyleSheet(Uri::base(true) . '/media/jui/css/icomoon.css');

    }
}