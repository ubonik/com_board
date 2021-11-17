<?php
defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Language\Text;

class BoardTableType extends Table
{
    public function __construct($db)
    {
        parent::__construct('#__board_types', 'id', $db);
    }

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