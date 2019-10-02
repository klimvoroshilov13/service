<?php
/**
 * Created by Nikolay N. Kazakov
 * File: PartsSearch.php
 * Date: 02.10.2019
 * Time: 6:42
 */

namespace app\models;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Parts;

/**
 * PartsSearch represents the model behind the search form about `app\models\Parts`.
 */
class PartsSearch extends Parts
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'part_request'], 'integer'],
            [['partname', 'measure', 'supplier', 'invoice', 'status'], 'safe'],
            [['number'], 'number'],
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
        $query = Parts::find();

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
            'part_request' => $this->part_request,
            'number' => $this->number,
        ]);

        $query->andFilterWhere(['like', 'partname', $this->partname])
            ->andFilterWhere(['like', 'measure', $this->measure])
            ->andFilterWhere(['like', 'supplier', $this->supplier])
            ->andFilterWhere(['like', 'invoice', $this->invoice])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}