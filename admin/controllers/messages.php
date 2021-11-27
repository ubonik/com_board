<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Utilities\ArrayHelper;


class BoardControllerMessages extends AdminController
{
    protected $text_prefix = 'COM_BOARD_MESSAGES';

    public function getModel($name = 'Message', $prefix = 'BoardModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function confirm()
    {
        Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

        $app = Factory::getApplication();
        $cid = $this->input->get('cid', [], 'array');

        $data = ['confirm' => 1, 'unconfirm' => 0];

        $task = $this->getTask();

        $value = ArrayHelper::getValue($data, $task, 0 , 'int');

        if ($cid) {

            $model = $this->getModel();
            ArrayHelper::toInteger($cid);
        }

        try {
            $model->confirm($cid[0], $value);

            if ($value == 1) {
                $text = "COM_BOARD_MESSAGE_CONFIRMED";
            }elseif($value == 0) {
                $text = "COM_BOARD_MESSAGE_UNCONFIRMED";
            }

            $this->setMessage(Text::_($text));
        } catch(RuntimeException $e) {
            $this->setMessage($e->getMessage(), 'error');

        }

        $this->setRedirect(Route::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));

    }
}