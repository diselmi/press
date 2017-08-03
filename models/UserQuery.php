<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[User]].
 *
 * @see User
 */
class UserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return User[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    
    public function onlyClients($db = null)
    {
        $query = $this->joinWith('role0');
        $query->andWhere(['=', 'nom', 'client']);
        return $query;
    }
    
    public function onlyAdmins($db = null)
    {
        $query = $this->joinWith('role0');
        $query->andWhere(['=', 'type', 'admin']);
        return $query;
    }
    
    public function adminsNoClients($db = null)
    {
        $query = $this->joinWith('role0');
        $query->andWhere(['=', 'role.type', 'admin']);
        $query->andWhere(['not',['role.nom' => 'client'] ]);
        return $query;
    }

    
    public function employeesOf($cId)
    {
        $query = $this->joinWith('role0');
        $query->where(['=', 'user.id', $cId]);
        $query->orWhere(['and', " role.type='client' ", "user.superieur=$cId"]);
        //$query->orWhere(['=', 'user.superieur', $cId]);
        
        return $query;
    }
   
    
}
