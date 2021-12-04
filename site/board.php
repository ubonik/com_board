<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;

$controller = BaseController::getInstance('Board');

JLoader::register('BoardHelper', JPATH_ADMINISTRATOR . '/components/com_board/helpers/board.php');
$input = Factory::getApplication()->input;

$controller->execute($input->getCmd('task', 'display'));

$controller->redirect();