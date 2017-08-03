<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Publication;
use yii\helpers\ArrayHelper;

/**
 * PublicationSearch represents the model behind the search form about `app\models\Publication`.
 */
class PublicationSearch extends Publication
{
    
    public $lang_search;
    public $cree_par_search;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'lang', 'type_contenu', 'contenu_doc', 'event'], 'integer'],
            [['titre', 'contenu_html', 'image', 'lang_search', 'cree_par_search'], 'safe'],
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
    public function search($params, $cId)
    {
        $client = Yii::$app->user->identity->superieur0;
        $team = User::findAll(["superieur" => $client->id]);
        array_push($team, User::findOne([$client->id]));
        $team = ArrayHelper::map($team, 'id', 'id');
        
        $query = Publication::find()->joinWith("lang0 l")->joinWith("creePar0 c");
        
        $query->andWhere(["in", "c.id", $team]);
        
        //var_dump($query);
        //Yii::$app->end();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        //$query->andWhere(['cree_par'=> $team ]);
        
        $dataProvider->sort->attributes['lang_search'] = [
            'asc' => ['l.nom' => SORT_ASC],
            'desc' => ['l.nom' => SORT_DESC],
        ];
        
        $dataProvider->sort->attributes['cree_par_search'] = [
            'asc' => ['c.prenom' => SORT_ASC],
            'desc' => ['c.prenom' => SORT_DESC],
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
            'lang' => $this->lang,
            'type_contenu' => $this->type_contenu,
            'contenu_doc' => $this->contenu_doc,
            'event' => $this->event,
        ]);

        $query->andFilterWhere(['like', 'titre', $this->titre])
            ->andFilterWhere(['like', 'contenu_html', $this->contenu_html])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['or', ['like', 'c.nom', $this->cree_par_search], ['like', 'c.prenom', $this->cree_par_search]] )
            ->andFilterWhere(['like', 'l.nom', $this->lang_search]);

        return $dataProvider;
    }
}
