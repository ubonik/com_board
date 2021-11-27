<?php
defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Factory;

HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('formbehavior.chosen', 'select');
?>

<form action="<?php echo Route::_('index.php?option=com_board&view=messages') ?>" method="POST"
      name="adminForm" id="adminForm">

    <?php if ($this->sidebar): ?>
        <div id="j-sidebar-container" class="span2">
            <?php echo $this->sidebar; ?>
        </div>
    <?php endif; ?>
    <div id="j-main-container" class="span10">

        <?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]) ?>

        <table class="table table-striped table-hover">

            <thead>
            <tr>
                <th width="1%"><?php echo Text::_('COM_BOARD_NUM'); ?></th>
                <th width="2%">
                    <?php echo HTMLHelper::_('grid.checkall'); ?>
                </th>
                <th width="90%">
                    <?php echo HTMLHelper::_('grid.sort', 'COM_BOARD_MESSAGES_TITLE', 'title', $this->listDirn, $this->listOrder); ?>
                </th>

                <th width="10%">
                    <?php echo HTMLHelper::_('grid.sort', 'COM_BOARD_MESSAGE_TOWN', 'town', $this->listDirn, $this->listOrder); ?>
                </th>
                <th width="10%">
                    <?php echo HTMLHelper::_('grid.sort', 'JAUTHOR', 'author_name', $this->listDirn, $this->listOrder); ?>
                </th>

                <th width="10%">
                    <?php echo HTMLHelper::_('grid.sort', 'COM_BOARD_MESSAGES_PRICE', 'price', $this->listDirn, $this->listOrder); ?>
                </th>

                <th width="10%">
                    <?php echo HTMLHelper::_('grid.sort', 'JCATEGORY', 'category', $this->listDirn, $this->listOrder); ?>
                </th>
                <th width="10%">
                    <?php echo HTMLHelper::_('grid.sort', 'COM_BOARD_TYPE_NAME', 'type', $this->listDirn, $this->listOrder); ?>
                </th>

                <th width="10%">
                    <?php echo HTMLHelper::_('grid.sort', 'JSTATUS', 'state', $this->listDirn, $this->listOrder); ?>
                </th>
                <th width="10%">
                    <?php echo HTMLHelper::_('grid.sort', 'COM_BOARD_MESSAGES_CONFIRM', 'confirm', $this->listDirn, $this->listOrder); ?>
                </th>
                <th width="10%">
                    <?php echo HTMLHelper::_('grid.sort', 'COM_BOARD_MESSAGES_HITS', 'hits', $this->listDirn, $this->listOrder); ?>
                </th>

                <th width="2%">
                    <?php echo HTMLHelper::_('grid.sort', 'COM_BOARD_MESSAGE_ID', 'id', $this->listDirn, $this->listOrder); ?>
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
                    <?php $canEdit = $this->canDo->get('core.edit') || ($this->canDo->get('core.edit.own')
                            && Factory::getUser()->get('id') == $val->id_user); ?>
                    <?php if ($canEdit) : ?>
                        <?php $link = Route::_('index.php?option=com_board&task=message.edit&id=' . $val->id);?>
                    <?php endif ?>
                    <tr>
                        <td><?php echo $this->pagination->getRowOffset($key); ?></td>
                        <td>
                            <?php echo HTMLHelper::_('grid.id', $key, $val->id); ?>
                        </td>
                        <td>
                            <?php if ($canEdit) : ?>
                                <?php echo HTMLHelper::_('link', $link, $val->title,
                                        array('title'=>Text::_('COM_BOARD_EDIT_MESSAGE'))) ?>
                            <?php else: ?>
                                <?php echo $val->title ?>
                            <?php endif ?>
                        </td>

                        <td>
                            <?php echo $val->town; ?>
                        </td>

                        <td>
                            <?php echo $val->author_name; ?>
                        </td>
                        <td>
                            <?php echo $val->price; ?>
                        </td>
                        <td>
                            <?php echo $val->category; ?>
                        </td>
                        <td>
                            <?php echo $val->type; ?>
                        </td>

                        <td>
                            <?php $canChange = ($this->canDo->get('core.edit.state')) || ($this->canDo->get('core.edit.state.own')
                                && Factory::getUser()->get('id') == $val->id_user); ?>
                            <?php echo JHtml::_('jgrid.published', $val->state, $key, 'messages.', $canChange, 'cb', $val->publish_up, $val->publish_down); ?>
                        </td>

                        <td>
                            <?php $canModerate = $this->canDo->get('core.edit.state') ?>
                            <?php echo BoardHelper::confirm_mes($val->confirm, $key, 'messages.', $canModerate) ?>
                        </td>

                        <td>
                            <?php echo $val->hits; ?>
                        </td>

                        <td align="center">
                            <?php echo $val->id; ?>
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

            <?php echo JHtml::_('form.token'); ?>
        </div>
    </div>

</form>
