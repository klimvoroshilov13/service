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
 * @property string $measure_name
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
            [['measure_name'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yii', 'ID'),
            'measure_name' => Yii::t('yii', 'Name Measure'),
            'flag' => Yii::t('yii', 'Flag'),
        ];
    }
}