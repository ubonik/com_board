<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Factory;

class BoardControllerMessage extends FormController
{
    /**
     * The prefix to use with controller messages.
     *
     * @var  string
     *
     * @since  2.0.0
     */
    protected $text_prefix = 'COM_BOARD_MESSAGE';

    /**
     * Method to check if you can add a new record.
     *
     * @param   array  $data  An array of input data.
     *
     * @return  boolean
     *
     * @since  2.7.0
     */
    protected function allowAdd($data = array())
    {
        $user = Factory::getUser();

        return $user->authorise('core.create', $this->option . '.category.' . $data['id_categories'])
            || $user->authorise('core.create.messages', $this->option . '.category.' . $data['id_categories']);
    }

    /**
     * Method to check if you can edit an existing record.
     *
     * Extended classes can override this if necessary.
     *
     * @param   array   $data  An array of input data.
     * @param   string  $key   The name of the key for the primary key; default is id.
     *
     * @return  boolean
     *
     * @since   1.6
     */
    protected function allowEdit($data = array(), $key = 'id')
    {
        $user = Factory::getUser();
        $userId = $user->get('id');
        $messageId = (int)$data[$key] ? $data[$key]: 0;

        if ($messageId) {
            if ($user->authorise('core.edit', 'com_board.message.' . $messageId)) {
                return true;
            }
            if ($user->authorise('core.edit.own', 'com_board.message.' . $messageId)) {
                $message = $this->getModel()->getItem('$messageId');

                if (empty($message)) {
                    return false;
                }
                $id = $message->id_user;
                if ($user->id ==$id) {
                    return true;
                }
            }
        }

        return parent::allowEdit($data, $key);
    }
}
