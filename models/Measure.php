<?php
/**
 * Created by Nikolay N. Kazakov
 * File: Measure.php
 * Date: 27.09.2019
 * Time: 11:53
 */

namespace app\models;

use Yii;

/**
 * This is the model class for table "measure".
 *
 * @property integer $id
 * @property string $name_measure
 * @property integer $flag
 */
class Measure extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'measure';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['flag'], 'integer'],
            [['name_measure'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yii', 'ID'),
            'name_measure' => Yii::t('yii', 'Name Measure'),
            'flag' => Yii::t('yii', 'Flag'),
        ];
    }
}