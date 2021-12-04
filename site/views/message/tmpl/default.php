<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

$images = json_decode($this->item->images);

HTMLHelper::_('jquery.framework');
?>

<div class="t_mess">
    <h4 class="title_p_mess">
        <?php echo $this->item->title;?>
    </h4>
    <p class="p_mess_cat">
        <span><strong>Категория:</strong> <?php echo $this->item->category;?></span>
        <span><strong>Тип объявления:</strong> <?php echo $this->item->type;?></span>
        <span><strong>Город:</strong> <?php echo $this->item->town;?></span></p>
    <p class="p_mess_cat">
        <span><strong>Дата добавления объявления:</strong> <?php echo $this->item->publish_up;?></span>
        <span><strong>Дата снятия с публикации:</strong><?php echo $this->item->publish_down;?></span>
        <span><strong>Цена:</strong><?php echo $this->item->price;?> </span>
        <span><strong>Автор:</strong> <a href="mailto:admin@mail.ru"><?php echo $this->item->author_name;?></a></span>
        <span><strong>Просмотров:</strong> <?php echo $this->item->hits;?> </span>
    </p>
    <p>

    <div class="flexslider">
        <ul class="slides">

            <?php foreach($images as $img) :?>
                <?php if(!empty($img)) :?>
                    <li data-thumb="<?php echo $this->item->params->get('img_path').'/'.$this->item->params->get('img_thumb').'/'.$img?>">
                        <img src="<?php echo $this->item->params->get('img_path').'/'.$img?>">
                    </li>
                <?php endif;?>
            <?php endforeach;?>

        </ul>
    </div>

    <?php echo $this->item->fulltext;?>

    </p>
</div>
