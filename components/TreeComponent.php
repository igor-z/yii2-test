<?php
namespace app\components;

use app\exceptions\InvalidTreeItemPathException;
use app\models\TreeItem;
use app\providers\TreeProvider;
use app\repositories\TreeRepository;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

class TreeComponent extends BaseObject
{
    /** @var TreeRepository */
    public $treeRepository;
    /** @var TreeProvider */
    public $treeProvider;

    public function save(array $rawItems)
    {
        Yii::$app->db->transaction(function () use($rawItems) {
            $this->savePlain($rawItems);
        });
    }

    private function savePlain($rawItems)
    {
        usort($rawItems, function ($item1, $item2) {
            return version_compare($item1['position'], $item2['position']);
        });

        $left = 1;
        $lastLevel = 1;
        $parents = [];

        /** @var TreeItem|null $prevItem */
        foreach ($rawItems as $rawItem) {
            $level = count(explode('.', $rawItem['position']));
            if ($level > $lastLevel) {
                if (!isset($prevItem)) {
                    throw new InvalidTreeItemPathException("Invalid tree item path: \"{$rawItem['position']}\"");
                }

                $parents[] = $prevItem;

                $left++;
            } elseif ($level < $lastLevel) {
                if (isset($prevItem)) {
                    $prevItem->rgt = $left + 1;
                    $prevItem->save();
                }

                /** @var TreeItem $parent */
                $parent = array_pop($parents);

                if (!$parent) {
                    throw new InvalidTreeItemPathException("Invalid tree item path: \"{$rawItem['position']}\"");
                }

                $parent->rgt =  $left + 2;
                $parent->save();

                $left += 3;
            } elseif (isset($prevItem)) {
                $prevItem->rgt = $left + 1;
                $prevItem->save();

                $left += 2;
            }

            $prevItem = new TreeItem();
            $prevItem->level = $level;
            $prevItem->lft = $left;
            $prevItem->title = $rawItem['title'];
            $prevItem->value = $rawItem['value'];

            $lastLevel = $level;
        }

        if (isset($prevItem)) {
            $prevItem->rgt = ++$left;
            $prevItem->save();
        }

        while ($parent = array_pop($parents)) {
            $parent->rgt = ++$left;
            $parent->save();
        }

        $root = new TreeItem();
        $root->level = 0;
        $root->lft = 0;
        $root->rgt = ++$left;
        $root->title = 'root';
        $root->value = '';
        $root->save();
    }
}