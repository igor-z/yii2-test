<?php
/**
 * @var array $items
 */

use yii\bootstrap\Html;

?>

<ul>
    <?php foreach ($items as $item):?>
        <li><?=Html::encode($item['title'])?></li>

        <?php if (!empty($item['children'])):?>
            <?=$this->render('_tree-items', [
                'items' => $item['children'],
            ])?>
        <?php endif?>
    <?php endforeach?>
</ul>
