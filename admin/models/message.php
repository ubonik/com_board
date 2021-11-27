<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Image\Image;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Component\ComponentHelper;

class BoardModelMessage extends AdminModel
{
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option.'.message',
            'message',
            [
                'control' => 'jform',
                'load_data' => $loadData
            ]
        );

        if (empty($form)) {

            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        $data = Factory::getApplication()->getUserState('com_board.edit.message.data',[]);

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getTable($name = 'Message', $prefix = 'BoardTable', $options = array())
    {
        return  parent::getTable($name, $prefix, array());
    }

    protected function prepareTable($table)
    {
        if ($table->created == 0 && $table->get('state') == 1) {
            $table->created = Factory::getDate()->toSql();
        }

        if ((int)$table->publish_up == 0 && $table->get('state') == 1) {
            $table->publish_up = Factory::getDate()->toSql();
        }

        if ((int)$table->publish_down == 0 && $table->get('state') == 1) {
            $table->publish_down = Factory::getDate('+1 week')->toSql();
        }
    }

    public function save($data) {
        $data['id_user'] = Factory::getUser()->id;
        $config = ComponentHelper::getParams('com_board');

        if (!trim($data['title'])) {
            $this->setError(Text::_('COM_BOARD_WARNING_PROVIDE_VALID_NAME'));

            return false;
        }

        if(trim($data['alias']) == '') {

            $data['alias'] = ApplicationHelper::stringURLSafe($data['title']);
        }

        foreach ($data['images'] as $k => $img) {
            if(empty($img)) {

                continue;
            }

            $path = JPATH_SITE . '/';
            $image = new Image($path . $img);
            $result = $image->generateThumbs(array('250x250'),2);

          //$image->createThumbs(['250x250', '1024x768', '800x600'], 2, $path . 'images/thumbs');
            if($result && is_array($result))  {
                $type = JImage::getImageFileProperties($path.$img)->type;
                if($type) {
                    $move = $result[0]->toFile($path.$config->get('img_path').'/'.$config->get('img_thumb').'/'.basename($img),$type);
                    if($move) {
                        $result[0]->destroy();
                        $image->destroy();
                    }
                }
            }
            $data['images'][$k] = basename($img);
        }

        $registry = new Registry();
        $registry->loadArray($data['images']);
        $data['images'] = (string) $registry;

        return parent::save($data);
    }

    public function confirm($cid, $value)
    {
        if (!parent::canEditState($value)) {

            $mes = Factory::getApplication()->enqueueMessage(
                Text::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'),
                'warning'
            );
            throw new RuntimeException($mes);
        }
        $table = $this->getTable();

        if ($table->load($cid)) {

            if (!$table->confirm($cid, $value)) {
                $this->setError($table->getError());
                return false;
            }
        } else {
            $this->setError($table->getError());
            return false;
        }
        $this->cleanCache();

        return true;
    }

    protected function canEditState($record)
    {
        $user = Factory::getUser();
        $userId = $user->get('id');
        $messageId = (int)$record->id ? $record->id : 0;

        if (!$messageId) {
            return parent::canEditState($record);
        }

        if ($user->authorise('core.edit.state', 'com_board')) {

            return true;
        }

        if ($user->authorise('core.edit.state.own', 'com_board')) {

            $message = $this->getItem($messageId);

            if (empty($message)) {
                return false;
            }

            $id_author = $message->id_user;

            if ($userId ==$id_author) {

                return true;
            }
        }
        $mes = Factory::getApplication()->enqueueMessage(
            Text::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'),
            'warning');
        throw new RuntimeException($mes);
    }

    public function getItem($pk = null)
    {
        $item = parent::getItem($pk);

        if ($item) {

            $registry = new Registry();
            $registry->loadString($item->images);

            $config = ComponentHelper::getParams('com_board');

            $item->images = $registry->toArray();

            foreach ($item->images as $k => $img) {
                $item->images[$k] = $config->get('img_path') . '/' . $config->get('img_thumb') . '/' . $img;
            }
        }

        return $item;
    }

}