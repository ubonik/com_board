<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Factory;

class BoardControllerMessage extends FormController
{
    protected function allowAdd($data = array())
    {
        $user = Factory::getUser();

        return $user->authorise('core.create', $this->option . '.category.' . $data['id_categories'])
            || $user->authorise('core.create.messages', $this->option . '.category.' . $data['id_categories']);
    }

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
        } else {
            return parent::allowEdit($data, $key);
        }

    }
}
