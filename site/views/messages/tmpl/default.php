<<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

HTMLHelper::_('behavior.framework');

HTMLHelper::_('bootstrap.framework');
HTMLHelper::_('bootstrap.loadCss');

//print_r(JUri::getInstance()->toString());
?>

<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString());?>" method="post" name="adminForm" id="adminForm">

    <?php if(is_array($this->items)) :?>
        <?php foreach($this->items as  $val) :?>
            <div class="t_mess">
                <h4 class="title_p_mess">
                    <?php $link = 'index.php?option=com_board&view=message&id='.$val->id?>
                    <a href="<?php echo $link;?>">
                        <?php echo $val->title;?>
                    </a>
                </h4>

                <p class="p_mess_cat">
                    <span><strong>Категория:</strong> <?php echo $val->category;?></span>
                    <span><strong>Тип объявления:</strong><?php echo $val->type;?> </span>
                    <span><strong>Город:</strong><?php echo $val->town;?> </span></p>
                <p class="p_mess_cat">
                    <span><strong>Дата добавления объявления:</strong><?php echo $val->publish_up;?></span>
                    <span><strong>Дата снятия с публикации:</strong><?php echo $val->publish_down;?> </span>
                    <span><strong>Цена:</strong><?php echo $val->price;?></span>
                    <span><strong>Автор:</strong><?php echo $val->author_name;?> </span>
                    <span><strong>Просмотров:</strong> <?php echo $val->hits;?> </span>
                </p>
                <p><img class="mini_mess" src="<?php echo $this->params->get('img_path').'/'.$this->params->get('img_thumb').'/'.$val->images->img?>">

                    <?php echo nl2br($val->introtext);?>

                </p>
            </div>
        <?php endforeach;?>

        <?php echo $this->pagination->getListFooter();?>
    <?php endif;?>

    <div class="pagination">
    </div>

</form>

