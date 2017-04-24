<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * @var string
     */
    public $nom_role;
    
    /**
     * @var string
     */
    public $nom_fonction;
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'lang', 'couleur_interface', 'role', 'fonction'], 'integer'],
            [['nom', 'prenom', 'mail', 'login', 'pass', 'logo', 'adresse'], 'safe'],
            [['nom_role', 'nom_fonction'], 'safe']
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
        $query = User::find()->joinWith('role0');
        
        

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $dataProvider->sort->attributes['nom_role'] = [
            'asc' => ['role.nom' => SORT_ASC],
            'desc' => ['role.nom' => SORT_DESC],
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
        ]);

        $query->andFilterWhere(['like', 'user.nom', $this->nom])
            ->orFilterWhere(['like', 'prenom', $this->prenom])
            ->andFilterWhere(['like', 'mail', $this->mail])
            ->andFilterWhere(['like', 'login', $this->login])
            ->andFilterWhere(['like', 'adresse', $this->adresse])
            ->andFilterWhere(['like', 'role.nom', $this->nom_role])
            ->andFilterWhere(['like', 'fonction.nom', $this->nom_fonction]);

        return $dataProvider;
    }
}
