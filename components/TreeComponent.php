<?php
namespace app\components;

use app\exceptions\InvalidTreeItemPathException;
use app\models\TreeItem;
use Yii;
use yii\base\BaseObject;

class TreeComponent extends BaseObject
{
    public function deleteAll()
    {
        TreeItem::deleteAll();
    }

    public function save(array $rawItems)
    {
        Yii::$app->db->transaction(function () use($rawItems) {
            usort($rawItems, function ($item1, $item2) {
                return version_compare($item1['position'], $item2['position']);
            });

            $left = 1;
            $prevDepth = 1;
            $parents = [];

            /** @var TreeItem|null $prevItem */
            foreach ($rawItems as $rawItem) {
                $depth = count(explode('.', $rawItem['position']));
                if ($depth > $prevDepth) {
                    if (!isset($prevItem)) {
                        throw new InvalidTreeItemPathException("Invalid tree item path: \"{$rawItem['position']}\"");
                    }

                    $parents[] = $prevItem;

                    $left++;
                } elseif ($depth < $prevDepth) {
                    if (isset($prevItem)) {
                        $prevItem->rgt = ++$left;
                        $prevItem->save();
                    }

                    $left++;
                    do {
                        /** @var TreeItem $parent */
                        $parent = array_pop($parents);

                        if (!$parent) {
                            throw new InvalidTreeItemPathException("Invalid tree item path: \"{$rawItem['position']}\"");
                        }

                        $parent->rgt =  $left;
                        $parent->save();

                        $left++;
                        $prevDepth--;
                    } while ($depth < $prevDepth);
                } elseif (isset($prevItem)) {
                    $prevItem->rgt = $left + 1;
                    $prevItem->save();

                    $left += 2;
                }

                $prevItem = new TreeItem();
                $prevItem->depth = $depth;
                $prevItem->lft = $left;
                $prevItem->title = $rawItem['title'];
                $prevItem->value = $rawItem['value'];

                $prevDepth = $depth;
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
            $root->depth = 0;
            $root->lft = 0;
            $root->rgt = ++$left;
            $root->title = 'root';
            $root->value = '';
            $root->save();
        });
    }

    /**
     * @param array $items
     * @return array | null
     */
    public function getTree()
    {
        $items = TreeItem::find()
            ->orderBy(['lft' => \SORT_ASC])
            ->asArray()
            ->all();

        return $this->buildTree($items);
    }

    /**
     * @param array $items
     * @return array | null
     */
    public function buildTree(array $items)
    {
        $tree = array(
            'children' => array(),
        );
        $parents = array(&$tree);
        foreach ($items as $item) {
            if (isset($prevItem) && $item["depth"] > $prevItem['depth']) {
                $prevItem['children'] = array();
                $parents[$prevItem['depth'] + 1] = &$prevItem;
            }

            $parents[$item['depth']]['children'][] = &$item;

            $prevItem = &$item;
            unset($item);
        }

        return reset($tree['children']);
    }
}