<?php
defined('_JEXEC') or die;

use Joomla\CMS\Component\Router\RouterBase;
use Joomla\CMS\Factory;

class BoardRouter extends RouterBase
{
    public function build(&$query)
    {
        $segments = [];

        if (!empty($query['Itemid'])) {

            $menuItem = $this->menu->getItem($query['Itemid']);
        }

        if (isset($menuItem) && $menuItem->component != 'com_board') {
            unset($query['Itemid']);
        }
        if (isset($query['view'])) {
            $view = $query['view'];
        } else {
            return $segments;
        }

        if(isset($query['filter_author']) || isset($query['filter_town'])) {

            $segments[] = $view;

            $segments[] = isset($query['filter_author']) ? 'author' : 'town';
            unset($query['view']);

            $segments[] = $query['filter_author'] ?? $query['filter_town'];
            if(isset($query['filter_author'])) {
                unset($query['filter_author']);
            }
            if(isset($query['filter_town'])) {
                unset($query['filter_town']);
            }

            return $segments;
        }

        //isset menu item for message
        if (isset($menuItem) && ($menuItem->query['view']) == $view && isset($query['id'])
            && (isset($menuItem->query['id']) == (int)$query['id'])) {
            unset($query['view']);

            if (isset($query['idcat'])) {
                unset($query['idcat']);
            }
            if (isset($query['idt'])) {
                unset($query['idt']);
            }
            unset($query['id']);

            return $segments;
        }

        ///edit meesages fronted
        if(isset($menuItem) && ($menuItem->query['view'] == $query['view'])) {

            if(empty($query['idcat']) && empty($query['idt'])) {
                unset($query['view']);
                if(isset($query['layout'])) {
                    unset($query['view']);
                    unset($query['layout']);

                    return $segments;
                }
            }
        }

        if(isset($menuItem) && (isset($menuItem->query['idcat']) || isset($menuItem->query['idt']) )) {

            unset($query['view']);

            if(isset($query['idcat'])) {
                list($catid, $catalias) = explode(':', $query['idcat']);
            }

            if(isset($query['idt'])) {
                list($typeid, $typealias) = explode(':', $query['idt']);
            }

            if($menuItem->query['idcat'] == $catid) {
                unset($query['idcat']);

                if(isset($query['idt'])) {
                    unset($query['idt']);
                }

                if(isset($query['id'])) {
                    $id = explode(':', $query['id']);
                    $segments[] = $id[0] . '-' . $id[1];
                    unset($query['id']);
                }

                return $segments;
            }

            if($menuItem->query['idt'] == $typeid) {
                unset($query['idt']);
                if(isset($query['idcat'])) {
                    unset($query['idcat']);
                }

                if(isset($query['id'])) {
                    $id = explode(':', $query['id']);
                    $segments[] = $id[0] . '-' . $id[1];
                    unset($query['id']);
                }

                return $segments;
            }
        }

        //unset($query['Itemid']);
    //    $segments = array();

        if(isset($query['view'])) {
            $segments[] = $query['view'];
            unset($query['view']);
        }

        if(isset($query['idcat']) && !isset($query['id'])) {
            $cid = explode(':', $query['idcat']);
            if (isset($cid[1])) {
                $segments[] = $cid[0] . '-' . $cid[1];
            } else {
                $segments[] = $cid[0];
            }
        }
        if(isset($query['idt']) && !isset($query['id'])) {

            $tid = explode(':', $query['idt']);

            if (isset($tid[1])) {
                $segments[] = $tid[0] . '-' . $tid[1];
            } else {
                $segments[] = $tid[0];
            }
        }

        if(isset($query['id'])) {
            $id = explode(':', $query['id']);
            $segments[] = $id[0] . '-' . $id[1];
            unset($query['id']);
        };
        unset($query['idt']);
        unset($query['idcat']);

        return $segments;
    }

    public function parse(&$segments)
    {
        $vars = array();
        $total = count($segments);

        for($i = 0; $i < $total;$i++) {
            $segments[$i]  = preg_replace('/-/',':', $segments[$i],1);
        }

        $item = $this->menu->getActive();

        $vars['option'] = 'com_board';

        $db = Factory::getDbo();
        if($total == 1) {
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('id_categories', 'id_types')));
            $query->from($db->quoteName('#__board_post'));
            $query->where($db->quoteName('id') . ' = ' . (int)$segments[0]);

            $db->setQuery($query);
            $message = $db->loadObject();

            if($message) {
                if(($item->query['idcat'] == $message->id_categories) ||($item->query['idt'] == $message->id_types)) {
                    $vars['view'] = 'message';
                    $vars['id'] =(int)$segments[0];

                    return $vars;
                }
            }
        }

        switch($segments[0]) {

            case 'messages':

                if(!$segments[1]) {
                    $vars['view'] = $segments[0];
                    return $vars;
                }

                if($segments[1] == 'author') {
                    $vars['view'] = $segments[0];
                    $vars['filter_author'] = $segments[2];
                    break;
                }
                elseif($segments[1] == 'town') {
                    $vars['view'] = $segments[0];
                    $vars['filter_town'] = $segments[2];
                    break;
                }

                list($id, $alias) = explode(':', $segments[1]);

                $query  = $db->getQuery(true);
                $query->select($db->quoteName(array('alias', 'id')));
                $query->from($db->quoteName('#__board_categories'));
                $query->where($db->quoteName('id') . ' = ' . $id);

                $db->setQuery($query);
                $category = $db->loadObject();

                if($category && $category->alias == $alias) {
                    $vars['view'] = $segments[0];
                    $vars['idcat'] = $id;
                }
                else {
                    $query = $db->getQuery(true)
                        ->select($db->quoteName(array('alias', 'id')))
                        ->from($db->quoteName('#__board_types'))
                        ->where($db->quoteName('id') . ' = ' . $id);
                    $db->setQuery($query);

                    $type = $db->loadObject();
                    if($type) {
                        if ($type->alias == $alias)
                        {
                            $vars['view'] = $segments[0];
                            $vars['idt'] = (int)$segments[1];
                        }
                    }
                }
                break;

            case 'message':
                $vars['view'] = $segments[0];

                list($id,$alias) = explode(':', $segments[1]);

                $query = $db->getQuery(true);
                $query->select($db->quoteName(array('alias', 'id')));
                $query->from($db->quoteName('#__board_post'));
                $query->where($db->quoteName('id') . ' = ' . (int) $id);

                $db->setQuery($query);
                $message = $db->loadObject();

                if($message) {
                    if ($message->alias == $alias)
                    {
                        $vars['id'] = (int) $id;
                    }
                }

                break;
        }

        return $vars;
    }
}
