<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class TreeItem
 * @property int $id
 * @property string $title
 * @property string $value
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 * @package app\models
 */
class TreeItem extends ActiveRecord
{
    public static function tableName()
    {
        return 'tree';
    }

    public function rules()
    {
        return [
            [['rgt', 'lft', 'id', 'depth'], 'integer'],
            [['title', 'value'], 'string', 'max' => 255],
        ];
    }
}