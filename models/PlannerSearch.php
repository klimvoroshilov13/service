<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Planner;

/**
 * PlannerSearch represents the model behind the search form about `app\models\Planner`.
 */
class PlannerSearch extends Planner
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['date', 'name_jobs', 'name_customers', 'name_status', 'name_performers1', 'name_performers2'], 'safe'],
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
        $query = Planner::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['date' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 7,
            ],
//            'sort' => [
//                'OrderBy' => [
//                    'date' => SORT_DESC,
//                ]
//            ],
        ]);

//        if (empty($dataProvider->sort->getAttributeOrders())) {
//            $dataProvider->query->orderBy(['id' => SORT_DESC]);
//        }
//
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,
            'info_text' => $this->info_text,
        ]);

        $query->andFilterWhere(['like', 'name_jobs', $this->name_jobs])
            ->andFilterWhere(['like', 'name_customers', $this->name_customers])
            ->andFilterWhere(['like', 'info_text', $this->info_text])
            ->andFilterWhere(['like', 'name_status', $this->name_status])
            ->andFilterWhere(['like', 'name_performers1', $this->name_performers1])
            ->andFilterWhere(['like', 'name_performers2', $this->name_performers2]);

        return $dataProvider;
    }
}
