<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Salle;

/**
 * SalleSearch represents the model behind the search form about `app\models\Salle`.
 */
class SalleSearch extends Salle
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'capacite', 'cree_par'], 'integer'],
            [['est_premium'], 'boolean'],
            [['nom', 'connectique', 'gouvernerat', 'adresse', 'vis_a_vis', 'est_premium'], 'safe'],
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
        $query = Salle::find();

        // add conditions that should always apply here
        $this->addClientConditions($query);
        
        //var_dump($query->all());
        //Yii::$app->end();
        
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
            'capacite' => $this->capacite,
            'cree_par' => $this->cree_par,
            'est_premium' => $this->est_premium,
        ]);
        
        //var_dump($params);
        //Yii::$app->end();

        $query->andFilterWhere(['like', 'nom', $this->nom])
            ->andFilterWhere(['like', 'connectique', $this->connectique])
            ->andFilterWhere(['like', 'gouvernerat', $this->gouvernerat])
            ->andFilterWhere(['like', 'adresse', $this->adresse])
            ->andFilterWhere(['like', 'vis_a_vis', $this->vis_a_vis]);

        return $dataProvider;
    }
    
    public function addClientConditions($query)
    {
        if (!Yii::$app->user->isGuest && $cUser = Yii::$app->user->identity ) {
            $premium = 0;
            if ($abonnement = Abonnement::findOne(['client'=>$cUser->superieur0->id])) {
                $premium = $abonnement->acces_salles? [0,1] : 0;
            }
            $liste_employes = \yii\helpers\ArrayHelper::getColumn(User::getTeamOf($cUser->id), "id");
            $liste_admins = \yii\helpers\ArrayHelper::getColumn(User::find()->adminsNoClients()->all(), "id");
            $query->andWhere('false');
            $query->orWhere(['cree_par'=>$liste_employes]);
            $query->orWhere([ 'and' ,  ['est_premium'=>$premium]  ,  ['cree_par'=>$liste_admins]  ]);
        }
        
    }
    
    
}
