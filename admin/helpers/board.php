<?php
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Access\Access;
use Joomla\CMS\Factory;
//use Joomla\Registry\Registry;

class BoardHelper
{
    public static function addSubMenu($viewName)
    {

        JHtmlSidebar::addEntry(
            Text::_('COM_BOARD_SUBMENU_CATEGORIES'),
            'index.php?option=com_board',
            $viewName == 'categories'
        );
        JHtmlSidebar::addEntry(
            Text::_('COM_BOARD_SUBMENU_TYPES'),
            'index.php?option=com_board&view=types',
            $viewName == 'types'
        );
        JHtmlSidebar::addEntry(
            Text::_('COM_BOARD_SUBMENU_MESSAGES'),
            'index.php?option=com_board&view=messages',
            $viewName == 'messages'
        );

        return JHtmlSidebar::render();
    }

    public static function confirm_mes($value, $i, $prefix = '', $can = false, $img1 = 'tick.png', $img0 = 'publish_x.png')
    {
        if (is_object($value)) {
           $value = $value->confirm;
        }

        $class = 'class="btn btn-micro hasTooltip ';

        if (!$can) {
            $class .= 'disabled';
        }
        $class .= '"';

        $img = $value ? $img1: $img0;
        $task = $value ? 'unconfirm': 'confirm';
        $alt = $value ? Text::_('COM_BOARD_UNCONFIRM'): Text::_('COM_BOARD_CONFIRM');
        $action = $value ? Text::_('COM_BOARD_ACTION_UNCONFIRM'): Text::_('COM_BOARD_ACTION_CONFIRM');

        $html = '<a ' . $class;

        if ($can) {
            $html .= ' onclick="return listItemTask(\'cb' . $i . '\', \'' . $prefix . $task . '\')" title="'
                . $action . '"';
        }
        $html .= '>' . HTMLHelper::_('image', 'admin/' . $img, $alt, null, true) . '</a>';

        return $html;
    }

    public static function getActions($messageId = 0)
    {
        //$result = new Registry();
        $result = new CMSObject();

        if (empty($messageId)) {
            $assetName = 'com_board';
        } elseif($messageId) {
            $assetName = 'com_board.message.' . $messageId;
        }

        $path = JPATH_ADMINISTRATOR . '/components/com_board/access.xml';
        $actions = Access::getActionsFromFile($path, "/access/section[@name='component']/");
        //var_dump($actions);

        foreach ($actions as $action) {
            $result->set($action->name, Factory::getUser()->authorise($action->name, $assetName));
        }

        return $result;
    }
}