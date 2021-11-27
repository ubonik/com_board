<?php
defined('_JEXEC') or die;

use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Factory;


FormHelper::loadFieldClass('list');

class JFormFieldBoardtype extends JFormFieldList
{
    protected $type = 'boardtype';

    protected function getOptions()
    {
        $options = [];

        $db = Factory::getDbo();
        $query = $db->getQuery(true);

        $query
            ->select('id AS value, name AS text')
            ->from('#__board_types')
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

        if ($row) {
           for ($i = 0; $i < count($row); $i++) {
               $options[] = $row[$i];
           }
        }

        return $options;
    }
}

