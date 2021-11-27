<?php

defined('_JEXEC') or die;

use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Factory;


FormHelper::loadFieldClass('groupedlist');

class JFormFieldBoardcat extends JFormFieldGroupedList
{
    protected $type = 'Boardcat';

    protected function getGroups()
    {
        $parent = parent::getGroups();

        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('id AS value, name AS text, parentid')
            ->from('#__board_categories')
            ->where('state=1')
        ;
        $db->setQuery($query);

        try {
            $row = $db->loadObjectList();
        }
        catch(RuntimeException $e){
            Factory::getApplication()->enqueueMessage($e->getMessage(),'error');
            return false;
        }

        $arr = [];

        if (!empty($parent)) {
            foreach ($parent as $option) {
                array_push($arr, $option);
            }
        }

        if ($row) {
            foreach ($row as $val) {
                if ($val->parentid !== '0') {
                    $val->items = [];
                }
            }

            $options = [];
            for ($i = 0; $i < count($row); $i++) {

                if ($row[$i]->parentid == 0) {
                    $options[$row[$i]->value] = $row[$i];
                }
            }

            for ($i = 0; $i < count($row); $i++) {

                if ($row[$i]->parentid != 0) {
                    $options[$row[$i]->parentid]->items[] = $row[$i];
                }
            }

            foreach ($options as $option) {
                 if (!isset($option->items) || count($option->items) == 0) {
                     unset($option);
                 }
                 if (isset($option->text)) {
                     $arr[$option->text] = $option->items;
                 }
            }
        }

        return $arr;
    }

}