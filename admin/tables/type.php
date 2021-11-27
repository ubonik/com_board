<?php
defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Language\Text;

class BoardTableType extends Table
{
    /**
     * Constructor.
     *
     * @param   JDatabaseDriver &$db  Database connector object
     *
     * @since  2.0.0
     */
    public function __construct($db)
    {
        parent::__construct('#__board_types', 'id', $db);
    }

    /**
     * Method to set the publishing state for a row or list of rows in the database table.
     *
     * The method respects checked out rows by other users and will attempt to checkin rows that it can after adjustments are made.
     *
     * @param   mixed    $pks     An optional array of primary key values to update. If not set the instance property value is used.
     * @param   integer  $state   The publishing state. eg. [0 = unpublished, 1 = published]
     * @param   integer  $userId  The user ID of the user performing the operation.
     *
     * @return  boolean  True on success; false if $pks is empty.
     *
     * @since   1.7.0
     */
    public function publish($pks = null, $state = 1, $userId = 0)
    {
        ArrayHelper::toInteger($pks);
        $state = (int) $state;

        if(empty($pks)) {
            throw new RuntimeException(Text::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
        }

        foreach($pks as $pk) {
            if(!$this->load($pk)) {
                throw new RuntimeException(Text::_('COM_BOARD_TABLE_ERROR_TYPE'));
            }
            $this->state = $state;

            if(!$this->store()) {
                throw new RuntimeException(Text::_('COM_BOARD_TABLE_ERROR_TYPE_STORE'));
            }
        }

        return true;
    }

}