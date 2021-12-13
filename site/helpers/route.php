<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Table\Table;


abstract class BoardRoute {

    public static function getMessageRoute($id, $catid = 0, $type = 0)
    {
        $link = '';
        $view = 'message';
        $link .= 'index.php?option=com_board&view=' . $view;
        $link .='&id='.$id;

        $link_m = null;
        $link_c = null;
        $link_t = null;


       // $id = explode(':',$id);
       // $id = $id[0];

        if (!empty($catid)) {
            $link .= '&idcat=' . $catid;
        }

        if(!empty($type)) {
            $link .= '&idt=' . $type;
        }

        $menu		= Factory::getApplication()->getMenu('site');
        $component  = ComponentHelper::getComponent('com_board');

        $attributes = array('component_id');
        $values     = array($component->id);

        $items = $menu->getItems($attributes, $values);

        if(!empty($items) && is_array($items)) {
            $tmp = explode(':',$id);
            $id_m = $tmp[0];

            foreach($items as $item) {

                if (isset($item->query) && isset($item->query['view']))
                {
                    if($item->query['view'] == $view) {

                        if($item->query['id'] == $id_m) {
                            //$link .='&Itemid='.$item->id;
                            //return $link;
                            $link_m = '&Itemid='.$item->id;
                        }
                    }
                    if($item->query['view'] == 'messages') {
                        $tmp_c = explode(':',$catid);
                        $tmp_t = explode(':',$type);

                        if(isset($item->query['idcat']) && $item->query['idcat']==$tmp_c[0]) {
                            //$link .='&Itemid='.$item->id;
                            //return $link;
                            $link_c = '&Itemid='.$item->id;
                        }
                        elseif(isset($item->query['idt']) && $item->query['idt'] == $tmp_t[0]) {
                            $link_t = '&Itemid='.$item->id;
                        }
                    }
                }
            }

            if($link_m) {
                $link .= $link_m;
                return $link;
            }
            if($link_c) {
                $link .= $link_c;
                return $link;
            }
            if($link_t) {
                $link .= $link_t;
                return $link;
            }
        }

        $link .= '&Itemid='.$menu->getDefault()->id;
        return $link;
    }

    public static function getCategoryRoute($catid = 0)	{
        $view = 'messages';
        $link = '';
        // Create the link

        list($cid, $calias) = explode(':',$catid);

        $link .= 'index.php?option=com_board&view='.$view.'&idcat=' . $catid;

        $menu		= Factory::getApplication()->getMenu('site');
        $component  = ComponentHelper::getComponent('com_board');

        $attributes = array('component_id');
        $values     = array($component->id);

        $items = $menu->getItems($attributes, $values);
        if(!empty($items) && is_array($items)) {
            foreach($items as $item) {
                if (isset($item->query) && isset($item->query['view']))	{
                    //echo $view;
                    if($item->query['view'] == $view) {
                        if(isset($item->query['idcat']) && $item->query['idcat'] == $cid) {
                            $link .= '&Itemid=' . $item->id;
                            return $link;
                        }
                    }
                }
            }
        }
        $link .= '&Itemid='.$menu->getDefault()->id;
        //echo '<p>'.$link.'</p>';
        return $link;
    }

    public static function getTypeRoute($catid = 0)	{
        $view = 'messages';
        $link = '';
        // Create the link

        list($cid,$calias) = explode(':',$catid);

        $link .= 'index.php?option=com_board&view='.$view.'&idt=' . $catid;

        $menu		= Factory::getApplication()->getMenu('site');
        $component  = ComponentHelper::getComponent('com_board');

        $attributes = array('component_id');
        $values     = array($component->id);

        $items = $menu->getItems($attributes, $values);
        if(!empty($items) && is_array($items)) {
            foreach($items as $item) {
                if (isset($item->query) && isset($item->query['view']))	{
                    //echo $view;
                    if($item->query['view'] == $view) {
                        if(isset($item->query['idt']) && $item->query['idt'] == $cid) {
                            $link .= '&Itemid=' . $item->id;
                            return $link;
                        }
                    }
                }
            }
        }
        $link .= '&Itemid='.$menu->getDefault()->id;
        //echo '<p>'.$link.'</p>';
        return $link;
    }

    public static function getFilterRoute($filter_type,$val)	{
        $view = 'messages';
        $link = '';

        $link .= 'index.php?option=com_board&view='.$view.'&filter_'.$filter_type.'=' . $val;

        return $link;
    }
}