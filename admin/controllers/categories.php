<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

class BoardControllerCategories extends AdminController
{
    /**
     * The prefix to use with controller messages.
     *
     * @var  string
     *
     * @since  2.0.0
     */
    protected $text_prefix = 'COM_BOARD_CATEGORIES';

    /**
     * Proxy for getModel.
     *
     * @param   string  $name    The model name.
     * @param   string  $prefix  The class prefix.
     * @param   array   $config  The array of possible config values.
     *
     * @return  BaseDatabaseModel|BoardModelCategory  A model object.
     *
     * @since  2.0.0
     */
    public function getModel($name = 'Category', $prefix = 'BoardModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

    /**
     * Removes an item.
     *
     * @return  void
     *
     * @throws
     *
     * @since   1.6
     */
    public function delete()
    {
        Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

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
