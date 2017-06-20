<?php

namespace app\models;

use Yii;

/**
 * This is the ActiveQuery class for [[Gallery]].
 *
 * @see Gallery
 */
class GalleryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Gallery[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Gallery|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    
    public function docsByGallery($gid=0, $ch="")
    {
        $db = Yii::$app->db;

        $docs = $db->createCommand(
            "select chemin as src, chemin as url, type from document where gallery=$gid ;"
        )->queryAll();
        
        foreach ($docs as &$doc) {
            $doc["src"] = $ch."/".$doc["src"];
            $doc["url"] = $ch."/".$doc["url"];
            if ($doc["type"] == "application/pdf") {
                $doc["src"] = "/images/profile_holder_m.jpg";
            }
        }
        
        return $docs;
    }

    
    
}
