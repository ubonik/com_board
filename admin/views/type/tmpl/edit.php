<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Layout\LayoutHelper;

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('formbehavior.chosen', 'select');

?>
<form action="<?php echo Route::_('index.php?option=com_board&layout=edit&id=' . (int)$this->item->id ) ?>"
      method="POST" name="adminForm" id="adminForm" >

    <div class="row-fluid">
        <div class="span9">
            <?php echo LayoutHelper::render('joomla.edit.title_alias', $this) ?>
        </div>
        <div class="span3">
            <?php echo LayoutHelper::render('joomla.edit.global', $this) ?>
        </div>
    </div>

    <?php echo $this->form->getField('id')->renderField() ?>

    <input name="task" type="hidden" value="type.edit" >
    <?php echo HTMLHelper::_('form.token') ?>
</form>