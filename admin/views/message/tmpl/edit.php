<?php
defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

HTMLHelper::_('behavior.formvalidation');
HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('formbehavior.chosen', 'select');
HTMLHelper::_('behavior.tooltip');

$app = Factory::getApplication();
$input = $app->input;
?>

<form action="<?php echo Route::_('index.php?option=com_board&layout=edit&id=') . $this->item->id ?>" method="post"
      id="adminForm" name="adminForm" class="form-validate" >

    <?php echo LayoutHelper::render('joomla.edit.title_alias', $this) ?>
    <div class="form-gorisontal">
        <?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', ['active' => 'general']) ?>
        <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'general', Text::_('COM_BOARD_MESSAGE_CONTENT', true)) ?>
        <div class="row-fluid">
            <div class="span9">
                <fieldset class="adminForm">
                    <?php echo $this->form->getInput('text'); ?> <!-- текстовый редактор  -->
                </fieldset>
            </div>
            <div class="span3">
                <?php echo LayoutHelper::render('joomla.edit.global', $this); ?> <!-- поле состояние -->
                <fieldset class="form-vertical">
                    <?php echo $this->form->renderFieldset('mesinfo');?> <!-- поля цена город категория тип -->
                </fieldset>
            </div>

        </div>
        <?php echo HTMLHelper::_('bootstrap.endTab') ?>

        <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'publishing', Text::_('COM_BOARD_FIELDSET_PUBLISHING', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">
                <?php echo LayoutHelper::render('joomla.edit.publishingdata', $this); ?> <!-- поля дат, количество просмотров -->
            </div>
            <div class="span6">
                <?php //echo JLayoutHelper::render('joomla.edit.metadata', $this); ?>
                <?php echo $this->form->renderFieldset('metadata') ?> <!-- поля метаданных -->
            </div>
        </div>
        <?php echo HTMLHelper::_('bootstrap.endTab') ?>

        <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'image', Text::_('COM_BOARD_FIELDSET_IMAGE', true)); ?>
        <div id="forimgs" class="forforms">
            <div class="span6">
                <?php //echo $this->form->getControlGroup('images'); ?>
                <?php foreach ($this->form->getGroup('images') as $field) : ?>
                    <?php echo $field->getControlGroup(); ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php echo HTMLHelper::_('bootstrap.endTab'); ?>

        <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'params', Text::_('JGLOBAL_FIELDSET_DISPLAY_OPTIONS', true)); ?>

        <?php echo $this->form->renderFieldset('params') ?>

        <?php echo HTMLHelper::_('bootstrap.endTab'); ?>

        <?php echo $this->form->getField('id')->renderField();?>

        <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'permissions', Text::_('COM_BOARD_FIELDSET_RULES', true)); ?>
        <?php echo $this->form->getInput('rules'); ?>
        <?php echo $this->form->getInput('asset_id'); ?>

        <?php echo HTMLHelper::_('bootstrap.endTab'); ?>

        <?php echo HTMLHelper::_('bootstrap.endTabSet'); ?>
    </div>

    <input type="hidden" name="task" value="message.edit" />
    <input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>" />
    <?php echo HTMLHelper::_('form.token'); ?>

</form>
