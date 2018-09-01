<?php

namespace smart\rbac\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use smart\rbac\models\Auth;

/**
 * AuthSearch represents the model behind the search form of `smart\rbac\models\Auth`.
 */
class AuthSearch extends Auth
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['auth_id', 'create_at', 'update_at'], 'integer'],
            [['auth_name', 'auth_desc', 'action_ids'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Auth::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'auth_id' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'auth_id' => $this->auth_id,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'auth_name', $this->auth_name])
            ->andFilterWhere(['like', 'auth_desc', $this->auth_desc])
            ->andFilterWhere(['like', 'action_ids', $this->action_ids]);

        return $dataProvider;
    }
}
