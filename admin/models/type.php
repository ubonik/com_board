<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Application\ApplicationHelper;

class BoardModelType extends AdminModel
{
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('board.type', 'type', ['control' => 'jform', 'load_data' => $loadData]);

        if (empty($form)) {

            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        $data = Factory::getApplication()->getUserState('com_board.edit.category.data', []);
        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function save($data)
    {
        if (!trim($data['name'])) {
            $this->setError(Text::_('COM_BOARD_WARNING_PROVIDE_VALID_NAME'));
            return false;
        }

        if(trim($data['alias']) == '') {
            $data['alias'] = ApplicationHelper::stringURLSafe($data['name']);
        }

        return parent::save($data);
    }

    public function getTable($name = 'Type', $prefix = 'BoardTable', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

}