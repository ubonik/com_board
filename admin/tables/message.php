<?php
defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Language\Text;
use http\Exception\RuntimeException;
use Joomla\CMS\Access\Rules;

class BoardTableMessage extends Table
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
        parent::__construct('#__board_post', 'id', $db);
    }

    /**
     * Method to bind an associative array or object to the Table instance.This
     * method only binds properties that are publicly accessible and optionally
     * takes an array of properties to ignore when binding.
     *
     * @param   array|object  $src     An associative array or object to bind to the Table instance.
     * @param   array|string  $ignore  An optional array or space separated list of properties to ignore while binding.
     *
     * @return  boolean  True on success.
     *
     * @since   1.7.0
     * @throws  \InvalidArgumentException
     */
    public function bind($src, $ignore = array())
    {
        $pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';

        $more = preg_match($pattern,$src['text']);

        if($more == 0) {
            $this->introtext = $src['text'];
            $this->fulltext = '';
        }
        elseif($more == 1) {
            list($this->introtext,$this->fulltext) = preg_split($pattern,$src['text'],2);
        }

        if (isset($src['params']) && is_array($src['params'])) {
            $registry = new Registry();
            $src['params'] = $registry->loadArray($src['params']);
            $src['params'] = (string) $registry;
        }

        if (isset($src['rules']) && is_array($src['rules'])) {
            $rules = new Rules($src['rules']);
            $this->setRules($rules);
        }

        return parent::bind($src, $ignore);
    }

    /**
     * Method to load a row from the database by primary key and bind the fields to the Table instance properties.
     *
     * @param   mixed    $keys   An optional primary key value to load the row by, or an array of fields to match.
     *                           If not set the instance property value is used.
     * @param   boolean  $reset  True to reset the default values before loading the new row.
     *
     * @return  boolean  True if successful. False if row not found.
     *
     * @since   1.7.0
     * @throws  \InvalidArgumentException
     * @throws  \RuntimeException
     * @throws  \UnexpectedValueException
     */
    public function load($keys = null, $reset = true)
    {
        if(parent::load($keys, $reset)) {
            $registry = new Registry();
            $this->params = $registry->loadString($this->params);
           // $this->images = $registry->loadString($this->images);

            return true;
        }

        return false;
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
    public function publish($pks = null, $state = 1, $userId = 0) {

        ArrayHelper::toInteger($pks);
        $state = (int)$state;

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

    public function confirm($cid, $value = 0)
    {
        if (empty($cid)) {
            throw new RuntimeException(Text::_("COM_BOARD_MESSAGE_CONFIRM_NO_ID"));
        }
        if (!isset($this->confirm)) {
            throw new RuntimeException(Text::_("COM_BOARD_MESSAGE_CONFIRM_NO_DATA"));
        }
        $this->confirm = $value;

        if (!$this->store()) {
            throw new RuntimeException(Text::_("COM_BOARD_MESSAGE_CONFIRM_ERROR_BD"));
        }

        return true;
    }

    /**
     * Method to compute the default name of the asset.
     * The default name is in the form table_name.id
     * where id is the value of the primary key of the table.
     *
     * @return  string
     *
     * @since   1.7.0
     */
    protected function _getAssetName()
    {
        return 'com_board.message.' . (int)$this->id;
    }

    /**
     * Method to return the title to use for the asset table.
     *
     * In tracking the assets a title is kept for each asset so that there is some context available in a unified access manager.
     * Usually this would just return $this->title or $this->name or whatever is being used for the primary name of the row.
     * If this method is not overridden, the asset name is used.
     *
     * @return  string  The string to use as the title in the asset table.
     *
     * @since   1.7.0
     */
    protected function _getAssetTitle()
    {
        return $this->title;
    }

    /**
     * Method to get the parent asset under which to register this one.
     *
     * By default, all assets are registered to the ROOT node with ID, which will default to 1 if none exists.
     * An extended class can define a table and ID to lookup.  If the asset does not exist it will be created.
     *
     * @param   Table    $table  A Table object for the asset parent.
     * @param   integer  $id     Id to look up
     *
     * @return  integer
     *
     * @since   1.7.0
     */
    protected function _getAssetParentId(Table $table = null, $id = null)
    {
        $assetParent = Table::getInstance('Asset');
        $assetParentId = $assetParent->getRootId();
        if ($this->id_categories && !empty($this->id_categories)) {
            $assetParent->loadByname('com_board.category.' . (int)$this->id_categories);
        }

        if ($assetParent->id) {
            $assetParentId = $assetParent->id;
        } else {
            $assetParent->loadByname('com_board');
        }

        if ($assetParent->id) {
            $assetParentId = $assetParent->id;
        }

        return $assetParentId;
    }
}