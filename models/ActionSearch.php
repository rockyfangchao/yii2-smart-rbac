<?php

namespace smart\rbac\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use smart\rbac\models\Action;

/**
 * ActionSearch represents the model behind the search form of `smart\rbac\models\Action`.
 */
class ActionSearch extends Action
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['action_id', 'create_at', 'update_at'], 'integer'],
            [['action_title', 'ctrl_title', 'module_title', 'route'], 'safe'],
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
        $query = Action::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'update_at' => SORT_DESC,
                    'action_id' => SORT_DESC,
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
            'action_id' => $this->action_id,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'action_title', $this->action_title])
            ->andFilterWhere(['like', 'ctrl_title', $this->ctrl_title])
            ->andFilterWhere(['like', 'module_title', $this->module_title])
            ->andFilterWhere(['like', 'route', $this->route]);

        return $dataProvider;
    }
}
