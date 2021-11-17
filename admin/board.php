<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;

$controller = BaseController::getInstance('board');
JLoader::register('BoardHelper', __DIR__ . '/helpers/board.php');
$controller->execute(Factory::getApplication()->input->get('task', 'display'));
$controller->redirect();
