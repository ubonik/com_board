<?php
defined('_JEXEC') or die;

use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

FormHelper::loadFieldClass('list');

class JFormFieldCategoryparent extends JFormFieldList
{
    protected $type = 'categoryparent';

    protected function getOptions()
    {
        $options = [];

        $db = Factory::getDbo();
        $query = $db->getQuery(true);

        $query->select($db->quoteName(['id', 'name', 'parentid'], ['value', 'text', 'parentid']));
        $query->from($db->quoteName('#__board_categories'));
        $query->where($db->quoteName('state') . '=' . $db->quote(1));

        $db->setQuery($query);

        try{
            $row = $db->loadObjectList();
        } catch (RuntimeException $e) {
            JError::raiseWarning(500, $e->getMessage());
        }

        $parent = new stdClass();
        $parent->text = Text::_('JGLOBAL_ROOT_PARENT');
        $parent->value = 0;

        array_push($options, $parent);

        if($row) {
            foreach ($row as $item) {
                if ($item->parentid === '0') {
                    array_push($options, $item);
                }
            }

        }

        return $options;
    }
}