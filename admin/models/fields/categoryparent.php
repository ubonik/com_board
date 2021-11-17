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
//echo $query;
        $db->setQuery($query);

        try{
            $row = $db->loadObjectList();
        } catch (RuntimeException $e) {
            JError::raiseWarning(500, $e->getMessage());
        }
//var_dump($row);
        $parent = new stdClass();
        $parent->text = Text::_('JGLOBAL_ROOT_PARENT');
        $parent->value = 0;

        array_push($options, $parent);

        if($row) {
           /* for ($i = 0; $i < count($row); $i++) {
                if ($row[$i]->parentid == 0) {
                   // echo $row[$i]->parentid;
                    array_push($options, $row[$i]);
                }
            }*/

            foreach ($row as $item) {
                if ($item->parentid === '0') {
                    array_push($options, $item);
                  //  $options[] = $item;
                }
            }

        }
//var_dump($options);
        return $options;
    }
}