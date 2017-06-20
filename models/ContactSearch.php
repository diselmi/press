<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Contact;

/**
 * ContactSearch represents the model behind the search form about `app\models\Contact`.
 */
class ContactSearch extends Contact
{
    public $r = 0;
    
    function __construct($rr) {
        parent::__construct();
        $this->r = $rr;
    }
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'etre_notifie', 'etre_contacte'], 'integer'],
            [['nom', 'prenom', 'numtel', 'email', 'adresse', 'rs', 'objet', 'contenu', 'apropos', 'entendu_par'], 'safe'],
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
        $query = Contact::findByCondition(['type' => $this->r]);

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
            'etre_notifie' => $this->etre_notifie,
            'etre_contacte' => $this->etre_contacte,
        ]);

        $query->andFilterWhere(['like', 'nom', $this->nom])
            ->andFilterWhere(['like', 'prenom', $this->prenom])
            ->andFilterWhere(['like', 'numtel', $this->numtel])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'adresse', $this->adresse])
            ->andFilterWhere(['like', 'rs', $this->rs])
            ->andFilterWhere(['like', 'objet', $this->objet])
            ->andFilterWhere(['like', 'contenu', $this->contenu])
            ->andFilterWhere(['like', 'apropos', $this->apropos])
            ->andFilterWhere(['like', 'entendu_par', $this->entendu_par]);
        
        $query->orderBy("envoye_le DESC");

        return $dataProvider;
    }
}
