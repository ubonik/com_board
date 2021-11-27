<?php
defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Access\Rules;

class BoardTableCategory extends Table
{
    public function __construct($db)
    {
        parent::__construct('#__board_categories', 'id', $db);
    }

    public function publish($pks = null, $state = 1, $userId = 0)
    {
        ArrayHelper::toInteger($pks);
        $state = (int) $state;

        if(empty($pks)) {
            throw new RuntimeException(Text::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
        }

        foreach($pks as $pk) {
            if($state === 0) {
                if($this->load(array('parentid'=>$pk,'state'=>'1'))) {
                    throw new RuntimeException(Text::_('COM_BOARD_MESSAGE_PUBLISH_CATEGORY_IS_PARENT'));
                }
            }
            if(!$this->load($pk)) {
                throw new RuntimeException(Text::_('COM_BOARD_TABLE_ERROR_CATEGORY'));
            }

            if($state === 1) {
                if($this->load(array('id'=>$this->parentid,'state'=>0),false)) {
                    throw new RuntimeException(Text::_('COM_BOARD_MESSAGE_PUBLISH_CATEGORY_IS_CHILD'));
                }
            }

            $this->state = $state;

            if(!$this->store()) {
                throw new RuntimeException(Text::_('COM_BOARD_TABLE_ERROR_CATEGORY_STORE'));
            }
        }

        return true;
    }

    public function bind($src, $ignore = array())
    {
        if (is_array($src['params'])) {
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

    public function load($keys = null, $reset = true)
    {
        if(parent::load($keys, $reset)) {
            $registry = new Registry();
            $this->params = $registry->loadString($this->params);

            return true;
        }

        return false;
    }

    protected function _getAssetName()
    {
        return 'com_board.category.' . (int)$this->id;
    }

    protected function _getAssetTitle()
    {
        return $this->name;
    }

    protected function _getAssetParentId(Table $table = null, $id = null)
    {
        $assetParent = Table::getInstance('Asset');
        $assetParentId = $assetParent->getRootId();
        $assetParent->loadByname('com_board');

        if ($assetParent->id) {
            $assetParentId = $assetParent->id;
        }

        return $assetParentId;
    }
}