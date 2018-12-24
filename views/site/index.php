<?php
/**
 * @var array $tree
 */

use yii\bootstrap\Html;
$this->title = 'Просмотр дерева';
$this->params['breadcrumbs'][] = $this->title;

$treeIsNotEmpty = $tree && !empty($tree['children']);
?>

<div>
    <h1><?=Html::encode($this->title)?></h1>

    <?=Html::a($treeIsNotEmpty ? 'Перегенерировать дерево' : 'Сгенерировать дерево', ['regenerate-tree'], [
        'class' => 'btn btn-success',
        'data' => [
            'method' => 'post',
        ],
    ]);?>

    <?=Html::a('Удалить дерево', ['delete-tree'], [
        'class' => 'btn btn-danger',
        'data' => [
            'method' => 'post',
        ],
    ]);?>

    <div class="well">
        <?php if ($treeIsNotEmpty):?>
            <?=$this->render('_tree-items', [
                'items' => $tree['children'],
            ])?>
        <?php else:?>
            Пусто
        <?php endif?>
    </div>
</div>
