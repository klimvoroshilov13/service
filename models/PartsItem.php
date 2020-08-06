<?php
/**
 * Created by Nikolay N. Kazakov
 * File: PartsItem.php.php
 * Date: 24.09.2019
 * Time: 14:43
 */

namespace app\models;

use Yii;

/**
 * This is the model class for table "parts".
 *
 * @property integer $id
 * @property integer $part_request
 * @property string $partname
 * @property double $number
 * @property string $measure
 * @property string $supplier
 * @property string $invoice
 * @property string $status
 *
 * @property Measure $measure0
 * @property PartsRequest $partRequest
 * @property Status $status0
 */
class PartsItem extends \yii\db\ActiveRecord
{
    public $customer;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parts_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['part_request', 'partname', 'number', 'measure'], 'required'],
            [['part_request'], 'integer'],
            [['number'], 'number'],
            [['partname', 'supplier'], 'string', 'max' => 127],
            [['measure'], 'string', 'max' => 15],
            [['invoice'], 'string', 'max' => 63],
            [['status'], 'string', 'max' => 31],
            [['measure'], 'exist', 'skipOnError' => true, 'targetClass' => Measure::className(), 'targetAttribute' => ['measure' => 'measure_name']],
            [['part_request'], 'exist', 'skipOnError' => true, 'targetClass' => PartsRequest::className(), 'targetAttribute' => ['part_request' => 'id']],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status' => 'status_name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yii', 'ID'),
            'part_request' => Yii::t('yii', 'Part Request'),
            'partname' => Yii::t('yii', 'Partname'),
            'number' => Yii::t('yii', 'Number'),
            'measure' => Yii::t('yii', 'Measure'),
            'supplier' => Yii::t('yii', 'Supplier'),
            'invoice' => Yii::t('yii', 'Invoice'),
            'status' => Yii::t('yii', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeasure0()
    {
        return $this->hasOne(Measure::className(), ['measure_name' => 'measure']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartRequest()
    {
        return $this->hasOne(PartsRequest::className(), ['id' => 'part_request']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(Status::className(), ['status_name' => 'status']);
    }

}