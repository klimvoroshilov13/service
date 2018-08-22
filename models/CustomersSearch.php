<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 *  Класс модели CustomersSearch(поиск контрагента)
 */
class CustomersSearch extends Customers
{
    /**
     *  Унаследованный метод rules класса модели Customers.
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'full_name', 'legal_address', 'mailing_address', 'inn', 'kpp'], 'safe'],
        ];
    }

    /**
     *  Унаследованный метод scenarios класса Model,
     * возращает определенные данные
     * в зависимости от сценария использования
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     *  Метод поиска нужного объекта модели Customers,
     * возвращает на страницу просмотра контрагентов,
     * полный список контрагентов web приложения.
     */
    public function search($params)
    {
        $query = Customers::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'full_name', $this->full_name])
            ->andFilterWhere(['like', 'legal_address', $this->legal_address])
            ->andFilterWhere(['like', 'mailing_address', $this->mailing_address])
            ->andFilterWhere(['like', 'inn', $this->inn])
            ->andFilterWhere(['like', 'kpp', $this->kpp]);

        return $dataProvider;
    }
}
