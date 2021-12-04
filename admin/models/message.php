<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Image\Image;
use Joomla\Registry\Registry;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Table\Nested;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Form\Form;

class BoardModelMessage extends AdminModel
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
        $data = Factory::getApplication()->getUserState('com_board.edit.message.data',[]);

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
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
    public function getTable($name = 'Message', $prefix = 'BoardTable', $options = array())
    {
        return  parent::getTable($name, $prefix, array());
    }

    /**
     * Prepare and sanitise the table prior to saving.
     *
     * @param   Table  $table  The Table object.
     *
     * @since  2.4.0
     */
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

    /**
     * Method to test whether a record can have its state changed.
     *
     * @param   object  $record  A record object.
     *
     * @return  boolean  True if allowed to change the state of the record. Defaults to the permission for the component.
     *
     * @since   1.6
     */
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

    /**
     * Method to get a single record.
     *
     * @param   integer  $pk  The id of the primary key.
     *
     * @throws  Exception
     *
     * @return  CMSObject|boolean  Object on success, false on failure.
     *
     * @since  2.0.0
     */
    public function getItem($pk = null)
    {
        $item = parent::getItem($pk);

        if ($item) {

            $registry = new Registry();
            $registry->loadString($item->images);

            $config = ComponentHelper::getParams('com_board');

            $item->images = $registry->toArray();

            foreach ($item->images as $k => $img) {
                if ($img) {
                    $item->images[$k] = $config->get('img_path') . '/' . $img;
                }
            }
        }

        return $item;
    }

}