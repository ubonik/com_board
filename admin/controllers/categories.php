<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

class BoardControllerCategories extends AdminController
{
    protected $text_prefix = 'COM_BOARD_CATEGORIES';

    public function getModel($name = 'Category', $prefix = 'BoardModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function delete()
    {
        Session::checkToken() or die(JText::_('JINVALID_TOKEN'));

        $app = Factory::getApplication();
        $cid = $this->input->get('cid', [], 'array');

        if (empty($cid) || !is_array($cid)) {
            $app->enqueueMessage(Text::_('COM_BOARD_MESSAGE_DELETE_CATEGORIES'), 'notice');
        }

        foreach ($cid as $id) {
            $flag = false;
            $db = Factory::getDbo();
            $query = $db->getQuery(true);
            $query->select('COUNT(*)')
                ->from('#__board_categories')
                ->where('parentid=' . $id)
            ;
            $db->setQuery($query);

            try {
                $row = $db->loadResult();

                if ($row > 0) {
                    $app->enqueueMessage(Text::_('COM_BOARD_MESSAGE_DELETE_CATEGORIES_IS_PARENT'), 'notice');
                    break;
                } elseif ($row === '0') {
                    $flag = true;
                }
            } catch(RuntimeException $e) {
                $app->enqueueMessage(Text::_('COM_BOARD_ERROR_DELETE_CATEGORIES'), 'error');
                break;
            }
        }
        if ($flag ===true) {
            parent::delete();
        }
        $this->setRedirect(Route::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
    }
}
