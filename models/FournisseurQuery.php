<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Fournisseur]].
 *
 * @see Fournisseur
 */
class FournisseurQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Fournisseur[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Fournisseur|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
