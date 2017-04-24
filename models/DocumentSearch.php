<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Document;

/**
 * DocumentSearch represents the model behind the search form about `app\models\Document`.
 */
class DocumentSearch extends Document
{
    /**
     * @var string
     */
    public $cree_par_field;
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'cree_par'], 'integer'],
            [['chemin', 'description', 'cree_par_field'], 'safe'],
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
        $query = Document::find()->joinWith("creePar c");

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
            'type' => $this->type,
            'cree_par' => $this->cree_par,
        ]);

        $query->andFilterWhere(['like', 'chemin', $this->chemin])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'c.nom', $this->cree_par_field])
            ->orFilterWhere(['like', 'c.prenom', $this->cree_par_field]);

        return $dataProvider;
    }
}
