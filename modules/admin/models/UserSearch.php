<?php

namespace app\modules\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 *  Класс модели UserSearch(Поиск пользователя)
 */
class UserSearch extends UserControl
{
    /**
     *  Унаследованный метод rules
     * класса модели UserControl и класса Model.
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'role'], 'safe'],
        ];
    }

    /**
    *  Унаследованный метод scenarios
     * класса модели UserControl и класса Model.
    */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     *  Метод поиска нужного объекта модели UserControl,
     * возвращает на страницу просмотра пользователей,
     * полный список пользователей web приложения.
     */
    public function search($params)
    {
        $query = UserControl::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'role', $this->role]);

        return $dataProvider;
    }
}
