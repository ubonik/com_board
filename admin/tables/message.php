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
    public function __construct($db)
    {
        parent::__construct('#__board_post', 'id', $db);
    }

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
//var_dump($src); exit;
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
      //  var_dump($cid);  exit;

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

    protected function _getAssetName()
    {
        return 'com_board.message.' . (int)$this->id;
    }

    protected function _getAssetTitle()
    {
        return $this->title;
    }

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