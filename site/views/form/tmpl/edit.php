<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Language\Text;

HTMLHelper::_('behavior.tabstate');
HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.calendar');
HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('formbehavior.chosen', 'select');
HTMLHelper::_('behavior.modal', 'a.modal_jform_contenthistory');

HTMLHelper::_('bootstrap.framework');
HTMLHelper::_('bootstrap.loadCss');

?>

<div class="t_mess">
    <form action="<?php echo Route::_('index.php?option=com_board&id=' . (int)$this->item->id); ?>" method="post"
          name="adminForm" id="adminForm" class="form-validate form-vertical">
        <div class="btn-toolbar">
            <div class="btn-group">
                <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('message.save')">
                    <span class="icon-ok"></span><?php echo Text::_('JSAVE') ?>
                </button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn" onclick="Joomla.submitbutton('message.cancel')">
                    <span class="icon-cancel"></span><?php echo Text::_('JCANCEL') ?>
                </button>
            </div>
        </div>

        <?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>

        <div class="form-horizontal">

            <?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>


            <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'general', Text::_('COM_BOARD_MESSAGE_CONTENT', true)); ?>
            <div class="row-fluid">
                <div class="span12">
                    <fieldset class="adminform">
                        <?php echo $this->form->getInput('text'); ?>
                    </fieldset>
                </div>
                <div class="span12">
                    <?php echo LayoutHelper::render('joomla.edit.global', $this); ?>
                    <fieldset class="form-vertical">
                        <?php echo $this->form->renderFieldset('mesinfo'); ?>
                    </fieldset>
                </div>
            </div>
            <?php echo HTMLHelper::_('bootstrap.endTab'); ?>

            <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'publishing', Text::_('COM_BOARD_FIELDSET_PUBLISHING', true)); ?>
            <div class="row-fluid form-horizontal-desktop">
                <div class="span6">
                    <?php echo LayoutHelper::render('joomla.edit.publishingdata', $this); ?>
                </div>
                <div class="span6">
                    <?php //echo LayoutHelper::render('joomla.edit.metadata', $this); ?>
                    <?php echo $this->form->renderFieldset('metadata') ?>
                </div>
            </div>
            <?php echo HTMLHelper::_('bootstrap.endTab'); ?>

            <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'image', Text::_('COM_BOARD_FIELDSET_IMAGE', true)); ?>
            <div id="forimgs" class="forforms">
                <div class="span6">

                    <?php echo $this->form->getControlGroup('images'); ?>
                    <?php foreach ($this->form->getGroup('images') as $field) : ?>
                        <?php echo $field->getControlGroup(); ?>
                    <?php endforeach; ?>
                </div>

            </div>
            <?php echo HTMLHelper::_('bootstrap.endTab'); ?>

            <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'params', Text::_('JGLOBAL_FIELDSET_DISPLAY_OPTIONS', true)); ?>

            <?php echo $this->form->renderFieldset('params') ?>

            <?php echo HTMLHelper::_('bootstrap.endTab'); ?>

            <?php echo $this->form->getField('id')->renderField(); ?>
            <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'permissions', Text::_('COM_BOARD_FIELDSET_RULES', true)); ?>
            <?php echo $this->form->getInput('rules'); ?>
            <?php echo $this->form->getInput('asset_id'); ?>

            <?php echo HTMLHelper::_('bootstrap.endTab'); ?>

            <?php echo HTMLHelper::_('bootstrap.endTabSet'); ?>
        </div>
        <input type="hidden" name="task" value="message.edit"/>
        <input type="hidden" name="return" value=""/>
        <?php echo HTMLHelper::_('form.token'); ?>
    </form>

</div>	

