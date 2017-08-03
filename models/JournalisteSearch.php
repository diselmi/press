<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Journaliste;

/**
 * JournalisteSearch represents the model behind the search form about `app\models\Journaliste`.
 */
class JournalisteSearch extends Journaliste
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cree_par'], 'integer'],
            [['nom', 'mail', 'numtel', 'photo', 'siteweb', 'facebook', 'twitter'], 'safe'],
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
        $query = Journaliste::find();

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

        $query->andFilterWhere(['like', 'nom', $this->nom])
            ->andFilterWhere(['like', 'mail', $this->mail])
            ->andFilterWhere(['like', 'numtel', $this->numtel])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'siteweb', $this->siteweb])
            ->andFilterWhere(['like', 'facebook', $this->facebook])
            ->andFilterWhere(['like', 'twitter', $this->twitter]);

        return $dataProvider;
    }
}
