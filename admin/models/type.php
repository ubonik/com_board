<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Table\Nested;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Form\Form;

class BoardModelType extends AdminModel
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
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('board.type', 'type', ['control' => 'jform', 'load_data' => $loadData]);

        if (empty($form)) {

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
    public function getTable($name = 'Type', $prefix = 'BoardTable', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

}