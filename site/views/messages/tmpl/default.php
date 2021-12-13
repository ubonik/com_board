<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.framework');

HTMLHelper::_('bootstrap.framework');
HTMLHelper::_('bootstrap.loadCss');
?>

<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString());?>" method="post" name="adminForm" id="adminForm">

    <?php if(is_array($this->items)) :?>
        <?php foreach($this->items as  $val) :?>
            <div class="t_mess">
                <h4 class="title_p_mess">
                    <?php $link = BoardRoute::getMessageRoute($val->slug,$val->catslug,$val->typeslug);?>
                    <a href="<?php echo Route::_( $link);?>">
                        <?php echo $val->title;?>
                    </a>
                </h4>

                <p class="p_mess_cat">
                    <span><strong>Категория:</strong><a href="<?php echo Route::_(BoardRoute::getCategoryRoute($val->catslug));?>"> <?php echo $val->category;?></a></span>
                    <span><strong>Тип объявления:</strong><a href="<?php echo Route::_(BoardRoute::getTypeRoute($val->typeslug));?>"><?php echo $val->type;?> </a></span>
                    <span><strong>Город:</strong><a href="<?php echo Route::_(BoardRoute::getFilterRoute('town',$val->town));?>"><?php echo $val->town;?> </a></span></p>
                <p class="p_mess_cat">
                    <span><strong>Дата добавления объявления:</strong><?php echo $val->publish_up;?></span>
                    <span><strong>Дата снятия с публикации:</strong><?php echo $val->publish_down;?> </span>
                    <span><strong>Цена:</strong><?php echo $val->price;?></span>
                    <span><strong>Автор:</strong><a href="<?php echo Route::_(BoardRoute::getFilterRoute('author',$val->id_user));?>"><?php echo $val->author_name;?></a> </span>
                    <span><strong>Просмотров:</strong> <?php echo $val->hits;?> </span>
                </p>
                <p><img class="mini_mess" src="<?php echo Uri::base(true) . '/' .$this->params->get('img_path').'/'.$this->params->get('img_thumb').'/'.$val->images->img?>">

                    <?php echo nl2br($val->introtext);?>

                </p>
            </div>
        <?php endforeach;?>

        <?php echo $this->pagination->getListFooter();?>
    <?php endif;?>

    <div class="pagination">
    </div>

</form>

