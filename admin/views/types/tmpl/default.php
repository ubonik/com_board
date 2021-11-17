<?php
defined('_JEXEC') or die;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
?>

<form action="<?php echo Route::_("index.php?option=com_board&view=types") ?>" method="POST" name="adminForm" id="adminForm">
    <?php if ($this->sidebar): ?>
        <div id="j-sidebar-container" class="span2">
            <?php echo $this->sidebar; ?>
        </div>
    <?php endif; ?>
    <div id="j-main-container" class="span10">
        <table class="table table-striped table-hover" >
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="5%"><?php echo HTMLHelper::_('grid.checkall') ?></th>
                    <th width="70%"><?php echo Text::_("COM_BOARD_TYPE_NAME") ?></th>
                    <th width="10%"><?php echo Text::_("JSTATUS") ?></th>
                    <th width="10%"><?php echo Text::_("COM_BOARD_TYPE_ID") ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($this->items)): ?>
                    <?php $i= 1; ?>
                    <?php foreach ($this->items as $key=>$item): ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo HTMLHelper::_('grid.id', $key, $item->id); ?></td>
                            <?php $link = Route::_("index.php?option=com_board&task=type.edit&id=" . $item->id); ?>
                            <td><?php echo HTMLHelper::_('link', $link, $item->name); ?></td>
                            <td><?php echo HTMLHelper::_('jgrid.published', $item->state, $key, 'types.') ?></td>
                            <td><?php echo $item->id; ?></td>
                        </tr>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

<input type="hidden" name="task" value="">
<input type="hidden" name="boxchecked" value="0">
<?php echo HTMLHelper::_('form.token') ?>
</form>
