<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Language\Text;

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('formbehavior.chosen', 'select');

?>

<form action="<?php echo Route::_('index.php?option=com_board&layout=edit&id=' . (int)$this->item->id ) ?>"
      method="POST" name="adminForm" id="adminForm" >

    <?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', ['active' => 'general']) ?>
        <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'general', Text::_('COM_BOARD_CATEGORY_CONTENT')) ?>


    <div class="row-fluid">
        <div class="span9">
            <?php echo LayoutHelper::render('joomla.edit.title_alias', $this) ?>
            <?php echo $this->form->getField('parentid')->renderField();?>
        </div>
        <div class="span3">
            <?php echo LayoutHelper::render('joomla.edit.global', $this) ?>
        </div>
    </div>

    <?php echo $this->form->getField('id')->renderField() ?>

    <?php echo HTMLHelper::_('bootstrap.endTab') ?>

    <?php echo LayoutHelper::render('joomla.edit.params', $this) ?>

    <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'permissions', Text::_('COM_BOARD_FIELDSET_RULES', true)); ?>
    <?php echo $this->form->getInput('rules'); ?>
    <?php echo $this->form->getInput('asset_id'); ?>

    <?php echo HTMLHelper::_('bootstrap.endTab'); ?>

    <?php echo HTMLHelper::_('bootstrap.endTabSet') ?>

    <input name="task" type="hidden" value="category.edit" >
    <?php echo HTMLHelper::_('form.token') ?>
</form>
