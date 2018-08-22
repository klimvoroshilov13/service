<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 *  Класс модели RequestsSearch(поиск заявок).
 */
class RequestsSearch extends Requests
{

    /**
     *  Унаследованный метод rules класса модели Requests.
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['date_start', 'date_end', 'name_customers', 'info', 'contacts', 'name_performers', 'name_status','name_contracts','name_user'], 'safe'],
        ];
    }

    /**
     *  Унаследованный метод scenarios класса модели Requests.
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     *  Метод поиска нужного объекта модели Requests(Заявки),
     * по умолчанию возвращает заявки находящиеся в работе
     * т.е со статусом "ожидание" и "выполняется" по запросу
     * пользователя web приложения возвращает весь список заявок.
     */
    public function search($params,$status)
    {
        $query = Requests::find();
        $dataProvider = new ActiveDataProvider([
                            'query' => $query->orderBy(['id' => SORT_DESC]),
                            'pagination' => [
                                'pageSize' => 10,
                            ],
                        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!($status<>'run')) {         //Проверка вывода всех заявок или только заявок в работе

            $dataProvider = new ActiveDataProvider([
                'query' => $query->orderBy(['name_status' => SORT_DESC,'id' => SORT_DESC]),
                'pagination' => [
                    'pageSize' => 10]
                ]);

            $query->Where(['name_status' => ['ожидание','отложена','выполняется'] ]);
        }

        $query->andFilterWhere([

            'id' => $this->id,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
        ]);

        $query->andFilterWhere(['like', 'name_customers', $this->name_customers])
            ->andFilterWhere(['like', 'info', $this->info])
            ->andFilterWhere(['like', 'contacts', $this->contacts])
            ->andFilterWhere(['like', 'name_performers', $this->name_performers])
            ->andFilterWhere(['like', 'name_status', $this->name_status])
            ->andFilterWhere(['like', 'name_contracts', $this->name_contracts])
            ->andFilterWhere(['like', 'name_user', $this->name_user]);
        return $dataProvider;
    }
}
