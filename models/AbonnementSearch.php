<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Abonnement;

/**
 * AbonnementSearch represents the model behind the search form about `app\models\Abonnement`.
 */
class AbonnementSearch extends Abonnement
{
    /**
     * @var string
     */
    public $nom_prenom_client;
    
    /**
     * @var string
     */
    public $nom_prenom_vav;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'etat', 'vis_a_vis', 'acces_salles', 'acces_journalistes'], 'integer'],
            [['date_debut', 'date_fin', 'nom_prenom_client', 'nom_prenom_vav'], 'safe'],
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
        $query = Abonnement::find()->joinWith('client0 client')->joinWith('vis_a_vis0 vav');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $dataProvider->sort->attributes['nom_prenom_client'] = [
            'asc' => ['client.nom' => SORT_ASC],
            'desc' => ['client.nom' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['nom_prenom_vav'] = [
            'asc' => ['vav.nom' => SORT_ASC],
            'desc' => ['vav.nom' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'etat' => $this->etat,
        ]);
        
         $query->andFilterWhere(['like', 'client.nom', $this->nom_prenom_client])
               ->orFilterWhere(['like', 'client.prenom', $this->nom_prenom_client])
               ->andFilterWhere(['like', 'date_debut', $this->date_debut])
               ->andFilterWhere(['like', 'date_fin', $this->date_fin])
               ->andFilterWhere(['like', 'vav.nom', $this->nom_prenom_vav])
               ->orFilterWhere(['like', 'vav.prenom', $this->nom_prenom_vav]);

        return $dataProvider;
    }
}
