<?php
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

HTMLHelper::_('bootstrap.framework');
HTMLHelper::_('bootstrap.loadCss');

HTMLHelper::_('behavior.framework');
?>


<div class="t_mess">

    <form action="<?php echo Route::_('index.php?option=com_board&view=usermessages'); ?>"
          method="post" name="adminForm" id="adminForm">

        <table class="table table-striped ">

            <thead>
            <tr>
                <th width="1%"><?php echo Text::_('COM_BOARD_NUM'); ?></th>
                <th width="2%">
                    <?php echo HTMLHelper::_('grid.checkall'); ?>
                </th>
                <th width="5%">
                    <?php echo Text::_('COM_BOARD_MESSAGES_TITLE') ?>
                </th>

                <th width="5%">
                    <?php echo Text::_('JCATEGORY'); ?>
                </th>
                <th width="5%">
                    <?php echo Text::_('COM_BOARD_TYPE_NAME'); ?>
                </th>

                <th width="10%">
                    <?php echo Text::_('JSTATUS'); ?>
                </th>
                <th width="10%">
                    <?php echo Text::_('COM_BOARD_MESSAGES_CONFIRM'); ?>
                </th>
                <th width="10%">
                    <?php echo Text::_('COM_BOARD_MESSAGES_HITS'); ?>
                </th>

            </tr>
            </thead>

            <tfoot>
            <tr>
                <td colspan="5">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
            </tfoot>

            <tbody>

            <?php if (!empty($this->items)) : ?>
                <?php foreach($this->items as $key =>$val) :?>
                    <?php

                    $canEdit = $this->canDo->get('core.edit') || ($this->canDo->get('core.edit.own')
                            && Factory::getUser()->get('id') == $val->id_user);

                    if ($canEdit) {
                        $link = Route::_('index.php?option=com_board&view=form&layout=edit&Itemid=238&id=' . $val->id);
                    }
                    ?>
                    <tr>
                        <td><?php echo $this->pagination->getRowOffset($key); ?></td>
                        <td>
                            <?php echo HTMLHelper::_('grid.id', $key, $val->id); ?>
                        </td>
                        <td>

                            <?php if ($canEdit) :?>
                                <?php echo HTMLHelper::_('link', $link, $val->title, array('title'=>Text::_('COM_BOARD_EDIT_MESSAGE')))  ?>
                            <?php else :?>
                                <?php echo $val->title;?>
                            <?php endif;?>

                        </td>

                        <td>
                            <?php echo $val->category; ?>
                        </td>
                        <td>
                            <?php echo $val->type; ?>
                        </td>

                        <td>
                            <?php
                            $canChange = ($this->canDo->get('core.edit.state')) || ($this->canDo->get('core.edit.state.own')
                                    && Factory::getUser()->get('id') == $val->id_user);
                            ?>

                            <?php echo HTMLHelper::_(
                                    'jgrid.published', $val->state, $key, 'usermessages.', $canChange,
                                    'cb', $val->publish_up, $val->publish_down); ?>

                            <?php echo HTMLHelper::_(
                                    'jgrid.action', $key, 'delete', 'usermessages.', 'delete', 'delete message',
                                    '', false, 'trash', '', $canChange); ?>

                        </td>

                        <td>

                            <?php
                            $canModerate = $this->canDo->get('core.edit.state');
                            ?>
                            <?php echo BoardHelper::confirm_mes($val->confirm,$key,'messages.',false);?>
                        </td>

                        <td>
                            <?php echo $val->hits; ?>
                        </td>

                    </tr>
                <?php endforeach;?>
            <?php endif;?>
            </tbody>

        </table>

        <div>
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />

            <input type="hidden" name="filter_order" value="<?php echo $this->listOrder?>" />
            <input type="hidden" name="filter_order_Dir" value="<?php echo $this->listDirn?>" />

            <?php echo HTMLHelper::_('form.token'); ?>
        </div>

    </form>
</div>