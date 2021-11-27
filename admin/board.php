<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

if (!Factory::getUser()->authorise('core.manage', 'com_board')) {
    throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'));
}

$controller = BaseController::getInstance('board');
$controller->registerTask('unconfirm', 'confirm');
JLoader::register('BoardHelper', __DIR__ . '/helpers/board.php');
$controller->execute(Factory::getApplication()->input->get('task', 'display'));
$controller->redirect();
