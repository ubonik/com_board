<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Application\ApplicationHelper;

class BoardModelCategory extends AdminModel
{
    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('board.category', 'category', ['control' => 'jform', 'load_data' => $loadData]);

        if (empty($form))
        {
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

    public function saveorder($pks = array(), $order = null)
    {
        // Initialize re-usable member properties
        $this->initBatch();

        $conditions = array();

        if (empty($pks))
        {
            return \JError::raiseWarning(500, \JText::_($this->text_prefix . '_ERROR_NO_ITEMS_SELECTED'));
        }

        $orderingField = $this->table->getColumnAlias('ordering');

        // Update ordering values
        foreach ($pks as $i => $pk)
        {
            $this->table->load((int) $pk);

            // Access checks.
            if (!$this->canEditState($this->table))
            {
                // Prune items that you can't change.
                unset($pks[$i]);
                \JLog::add(\JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'), \JLog::WARNING, 'jerror');
            }
            elseif ($this->table->$orderingField != $order[$i])
            {
                $this->table->$orderingField = $order[$i];

                if ($this->type)
                {
                    $this->createTagsHelper($this->tagsObserver, $this->type, $pk, $this->typeAlias, $this->table);
                }

                if (!$this->table->store())
                {
                    $this->setError($this->table->getError());

                    return false;
                }


            }
        }

        // Clear the component's cache
        $this->cleanCache();

        return true;
    }


    public function getTable($name = 'Category', $prefix = 'BoardTable', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

}