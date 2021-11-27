<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Table\Nested;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Form\Form;

class BoardModelCategory extends AdminModel
{
    /**
     * Abstract method for getting the form from the model.
     *
     * @param   array    $data      Data for the form.
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
     *
     * @throws  Exception
     *
     * @return  Form|boolean  A Form object on success, false on failure.
     *
     * @since  2.0.0
     */
    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('board.category', 'category', ['control' => 'jform', 'load_data' => $loadData]);

        if (empty($form))
        {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @throws  Exception
     *
     * @return  mixed  The data for the form.
     *
     * @since  2.0.0
     */
    protected function loadFormData()
    {
        $data = Factory::getApplication()->getUserState('com_board.edit.category.data', []);
        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Method to save the form data.
     *
     * @param   array  $data  The form data.
     *
     * @throws  Exception
     *
     * @return  boolean  True on success.
     *
     * @since  2.0.0
     */
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

    /**
     * Method to save the reordered nested set tree.
     *
     * @param   array    $idArray    An array of primary key ids.
     * @param   integer  $lft_array  The lft value
     *
     * @throws  Exception
     *
     * @return  boolean  False on failure or error, True otherwise.
     *
     * @since  2.0.0
     */
    public function saveorder($pks = array(), $order = null)
    {
        // Initialize re-usable member properties
        $this->initBatch();

        $conditions = array();

        if (empty($pks))
        {
            return \JError::raiseWarning(500, Text::_($this->text_prefix . '_ERROR_NO_ITEMS_SELECTED'));
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
                \JLog::add(Text::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'), \JLog::WARNING, 'jerror');
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

    /**
     * Returns a Table object, always creating it.
     *
     * @param   string  $type    The table type to instantiate
     * @param   string  $prefix  A prefix for the table class name.
     * @param   array   $config  Configuration array for model.
     *
     * @return  Table|Nested   A database object.
     *
     * @since  2.0.0
     */
    public function getTable($name = 'Category', $prefix = 'BoardTable', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

}