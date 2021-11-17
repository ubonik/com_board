<?php
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

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
}