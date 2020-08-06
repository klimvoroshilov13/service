<?php
/**
 * Created by Nikolay N. Kazakov
 * File: PartsItemSearch.php
 * Date: 02.10.2019
 * Time: 6:42
 */

namespace app\models;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PartsItem;

/**
 * PartsItemSearch represents the model behind the search form about `app\models\PartsItem`.
 */
class PartsSearch extends PartsItem
{
    /**
     * @inheritdoc
     */


        public function rules()
    {
        return [
            [['id','part_request'], 'integer'],
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
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PartsItem::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'partname', $this->partname])
            ->andFilterWhere(['like', 'supplier', $this->supplier])
            ->andFilterWhere(['like', 'customer', $this->part_request])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}