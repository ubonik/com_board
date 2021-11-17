<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('formbehavior.chosen', 'select');
?>
<form action="<?php echo Route::_('index.php?option=com_board&view=categories') ?>" method="POST"
      name="adminForm" id="adminForm" >

    <?php if ($this->sidebar): ?>
        <div id="j-sidebar-container" class="span2">
            <?php echo $this->sidebar; ?>
        </div>
    <?php endif; ?>

    <div id="j-main-container" class="span10">
        <table class="table table-striped table-hover ">
            <thead>
                <tr>
                    <th style="width: 1%">№-</th>
                    <th style="width: 2%"><?php echo HTMLHelper::_('grid.checkall') ?></th>

                    <th style="width: 70%">
                        <?php echo HTMLHelper::_('grid.sort', 'COM_BOARD_CATEGORIES_NAMES',
                            'name', $this->listDirn, $this->listOrder) ?>
                    </th>
                    <th style="width: 10%">
                        <?php echo HTMLHelper::_('grid.sort', 'JSTATUS',
                            'state', $this->listDirn, $this->listOrder) ?>
                    </th>
                    <th style="width: 5%">
                        <?php echo HTMLHelper::_('grid.sort', 'JGRID_HEADING_ORDERING',
                            'ordering', $this->listDirn, $this->listOrder) ?>
                        <?php if ($this->saveOrder): ?>
                            <?php echo HTMLHelper::_('grid.order', $this->categories, 'filesave.png', 'categories.saveorder') ?>
                        <?php endif; ?>
                    </th>
                    <th style="width: 12%">
                        <?php echo HTMLHelper::_('grid.sort', 'COM_BOARD_CATEGORY_ID',
                            'id', $this->listDirn, $this->listOrder) ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($this->items)): ?>
                <!-- i - обязательно с нуля, иначе работать не будет     -->
                    <?php $i = 0; ?>
                    <?php foreach ($this->items as $id => $cat): ?>
                    <?php if (isset($cat['name'])): ?>
                    <?php $link = Route::_("index.php?option=com_board&task=category.edit&id=" . $id); ?>
                    <tr>
                        <td><?= $i; ?></td>
                        <td><?php echo HTMLHelper::_('grid.id', $i, $id); ?></td>
                        <td><?php  echo HTMLHelper::_('link', $link, $cat['name']) ?></td>
                        <td><?php echo HTMLHelper::_('jgrid.published', $cat['state'], $i, 'categories.'); ?></td>
                        <td>

                            <?php if($this->saveOrder) :?>
                                <?php if($this->listDirn = 'asc') :?>
                                    <span><?php echo $this->pagination->orderUpIcon($i,true,'categories.orderup','JLIB_HTML_MOVE_UP',$this->saveOrder)?></span>
                                    <span><?php echo $this->pagination->orderDownIcon($i,$this->pagination->total,true,'categories.orderdown','JLIB_HTML_MOVE_DOWN',$this->saveOrder)?></span>
                                <?php elseif($this->listDirn = 'desc') :?>

                                    <span><?php echo $this->pagination->orderUpIcon($i,true,'categories.orderdown','JLIB_HTML_MOVE_UP',$this->saveOrder)?></span>
                                    <span><?php echo $this->pagination->orderDownIcon($i,$this->pagination->total,true,'categories.orderup','JLIB_HTML_MOVE_DOWN',$this->saveOrder)?></span>

                                <?php endif;?>
                            <?php endif;?>

                            <?php $disabled = $this->saveOrder? '': 'disabled="disabled"' ?>
                            <?php //$cat['ordering'] = !is_null($cat['ordering']) ?: 0;  ?>
                            <input type="text" name="order[]" value="<?php echo $cat['ordering'] ?>" <?php echo  $disabled; ?> >

                        </td>
                        <td><?php echo $id; ?></td>
                    </tr>
                            <?php $i++; ?>
                     <?php endif; ?>
                    <?php if (isset($cat['next']) && is_array($cat['next'])): ?>
                        <?php $k = "== "; ?>
                        <?php foreach ($cat['next'] as $sub): ?>
                            <?php $link = Route::_("index.php?option=com_board&task=category.edit&id=" . $sub['id']); ?>
                            <tr>
                                <td><?= $i; ?></td>
                                <td><?php echo HTMLHelper::_('grid.id', $i, $sub['id']); ?></td>
                                <td><?php  echo HTMLHelper::_('link', $link, $k . $sub['name']) ?></td>
                                <td><?php echo HTMLHelper::_('jgrid.published', $sub['state'], $i, 'categories.'); ?></td>
                                <td>
                                    <?php if($this->saveOrder) :?>
                                        <?php if($this->listDirn = 'asc') :?>
                                            <span><?php echo $this->pagination->orderUpIcon($i,true,'categories.orderup','JLIB_HTML_MOVE_UP',$this->saveOrder)?></span>
                                            <span><?php echo $this->pagination->orderDownIcon($i,$this->pagination->total,true,'categories.orderdown','JLIB_HTML_MOVE_DOWN',$this->saveOrder)?></span>
                                        <?php elseif($this->listDirn = 'desc') :?>

                                            <span><?php echo $this->pagination->orderUpIcon($i,true,'categories.orderdown','JLIB_HTML_MOVE_UP',$this->saveOrder)?></span>
                                            <span><?php echo $this->pagination->orderDownIcon($i,$this->pagination->total,true,'categories.orderup','JLIB_HTML_MOVE_DOWN',$this->saveOrder)?></span>

                                        <?php endif;?>
                                    <?php endif;?>

                                    <?php $disabled = $this->saveOrder? '': 'disabled="disabled"' ?>
                                    <?php //$sub['ordering'] = !is_null($sub['ordering']) ?: 0;  ?>
                                    <input type="text" name="order[]" value="<?php echo $sub['ordering']; ?>" <?= $disabled; ?> >
                                </td>
                                <td><?php echo $sub['id']; ?></td>
                            </tr>
                            <?php $i++; ?>

                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>

            <tfoot >
                <tr>
                    <td  colspan="5">
                        <div style="float: left">
                            <?php echo $this->pagination->getListFooter() ?>
                        </div>
                        <div style="float: right">
                            <?php echo $this->pagination->getLimitBox() ?>
                        </div>
                        <?php echo $this->pagination->getPagesCounter() ?>
                    </td>

                </tr>

            </tfoot>
        </table>

        <input type="hidden" name="task" value="">
        <input type="hidden" name="boxchecked" value="0">

        <input type="hidden" name="filter_order" value="<?php echo $this->listOrder ?>">
        <input type="hidden" name="filter_order_Dir" value="<?php echo $this->listDirn ?>">

        <?php echo HTMLHelper::_('form.token') ?>
    </div>
</form>