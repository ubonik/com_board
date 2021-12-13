<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Factory;

class BoardViewMessage extends HtmlView {

    protected $item;
    protected $state;

    public function display($tpl = null) {

        $this->item = $this->get('Item');
        $this->state = $this->get('State');

        if (count($errors = $this->get('Errors'))) {

            JError::raiseWarning(500, implode("\n", $errors));

            return false;
        }

        $model = $this->getModel();
        $model->hit();

         parent::display($tpl);
         $this->setDocument();
    }

    protected function setDocument() {
        $document = Factory::getDocument();

        $document->addScript(Uri::root(true).'/components/com_board/assets/js/jquery.flexslider.js');
        $document->addStyleSheet(Uri::root(true).'/components/com_board/assets/css/flexslider.css');

        $script = "jQuery(window).load(function() {
				  jQuery('.flexslider').flexslider({
				    animation: 'slide',
				    controlNav: 'thumbnails'
				  });
				});";

        $document->addScriptDeclaration($script);
    }
}