<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Media;

/**
 * MediaSearch represents the model behind the search form about `app\models\Media`.
 */
class MediaSearch extends Media
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pr_value', 'cree_par'], 'integer'],
            [['tv', 'radio', 'j_papier', 'j_electronique'], 'boolean'],
            [['nom', 'adresse', 'mail', 'numtel', 'logo', 'siteweb', 'facebook', 'twitter', 'date_creation'], 'safe'],
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
        //var_dump($params);
        $query = Media::find();

        // add conditions that should always apply here
        //$this->addClientConditions($query);
        
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $dataProvider->sort->attributes['pr_value'] = [
            'asc' => ['pr_value' => SORT_ASC],
            'desc' => ['pr_value' => SORT_DESC],
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
            'pr_value'  => $this->pr_value,
            'tv' => $this->tv,
            'radio' => $this->radio,
            'j_papier' => $this->j_papier,
            'j_electronique' => $this->j_electronique,
        ]);

        $query->andFilterWhere(['like', 'nom', $this->nom])
            ->andFilterWhere(['like', 'adresse', $this->adresse])
            ->andFilterWhere(['like', 'mail', $this->mail])
            ->andFilterWhere(['like', 'numtel', $this->numtel])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'siteweb', $this->siteweb])
            ->andFilterWhere(['like', 'facebook', $this->facebook])
            ->andFilterWhere(['like', 'twitter', $this->twitter])
            ->andFilterWhere(['like', 'date_creation', $this->date_creation]);

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
