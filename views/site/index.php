<?php
/**
 * @var array $tree
 */

use yii\bootstrap\Html;
$this->title = 'Просмотр дерева';
$this->params['breadcrumbs'][] = $this->title;

$treeIsEmpty = !$tree || empty($tree['children']);
?>

<div>
    <h1><?=Html::encode($this->title)?></h1>

    <?=Html::a($treeIsEmpty ? 'Сгенерировать' : 'Перегенерировать', ['regenerate-tree'], [
        'class' => 'btn btn-success',
        'data' => [
            'method' => 'post',
        ],
    ]);?>

    <?php if (!$treeIsEmpty):?>
        <?=Html::a('Удалить', ['delete-tree'], [
            'class' => 'btn btn-danger',
            'data' => [
                'method' => 'post',
            ],
        ]);?>
    <?php endif?>

    <div class="well">
        <?php if ($treeIsEmpty):?>
            Пусто
        <?php else:?>
            <?=$this->render('_tree-items', [
                'items' => $tree['children'],
            ])?>
        <?php endif?>
    </div>
</div>
