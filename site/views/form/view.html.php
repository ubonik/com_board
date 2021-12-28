<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

class BoardViewForm extends HtmlView
{
    protected $form;
    protected $item;
    protected $script;

    public function display($tpl = null)
    {
        $this->form = $this->get('form');
        $this->item = $this->get('item');
        $this->script = $this->get('script');

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));

            return false;
        }

        parent::display($tpl);
        $this->setDocument();
    }

    protected function setDocument()
    {
        $document = Factory::getDocument();
        $document->addStyleSheet(Uri::base(true) . '/media/jui/css/icomoon.css');
    }

}