<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "publication".
 *
 * @property integer $id
 * @property integer $lang
 * @property string $titre
 * @property integer $type_contenu
 * @property string $contenu_html
 * @property integer $contenu_doc
 * @property string $image
 * @property integer $event
 * @property integer $cree_par
 *
 * @property Document $contenuDoc
 * @property Langue $lang0
 * @property User $creePar0
 */
class Publication extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $champ_doc;
    
    /**
     * @var UploadedFile
     */
    public $champ_image;
    
    public $tmp_id;
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'publication';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['lang', 'titre', 'event'], 'required'],
            [['lang', 'titre', 'type_contenu'], 'required'],
            [['lang', 'type_contenu', 'contenu_doc', 'event'], 'integer'],
            [['champ_doc'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf, png, jpg, jpeg'],        
            [['contenu_html'], 'string'],
            [['titre', 'image'], 'string', 'max' => 256],
            [['champ_image'], 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['contenu_doc'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['contenu_doc' => 'id']],
            [['lang'], 'exist', 'skipOnError' => true, 'targetClass' => Langue::className(), 'targetAttribute' => ['lang' => 'id']],
            [['cree_par'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['cree_par' => 'id']],
            ['contenu_html', 'validateContenuHtml'],
            ['champ_doc', 'validateContenuDoc'],
            
        ];
    }
    
    public function validateContenuHtml($attribute, $params, $validator)
    {
        //var_dump($attribute);
        //Yii::$app->end();
        if ($this->type_contenu == 0 && empty($this->contenu_html)  ) {
            $this->addError($attribute, 'Le contenu html est vide');
            var_dump($this->errors);
        Yii::$app->end();
        }
        
    }
    
    public function validateContenuDoc($attribute, $params, $validator)
    {
        if ($this->type_contenu == 1 && !$this->champ_doc  ) {
            $this->addError($attribute, 'Le document est vide');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'lang' => Yii::t('app', 'Lang'),
            'titre' => Yii::t('app', 'Titre'),
            'type_contenu' => Yii::t('app', 'Type Contenu'),
            'contenu_html' => Yii::t('app', 'Contenu Html'),
            'contenu_doc' => Yii::t('app', 'Contenu Doc'),
            'image' => Yii::t('app', 'Image representative'),
            'champ_image' => Yii::t('app', 'Image representative'),
            'champ_doc' => Yii::t('app', 'Document'),
            'event' => Yii::t('app', 'Evenement'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContenuDoc()
    {
        return $this->hasOne(Document::className(), ['id' => 'contenu_doc']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLang0()
    {
        return $this->hasOne(Langue::className(), ['id' => 'lang']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreePar0()
    {
        return $this->hasOne(User::className(), ['id' => 'cree_par']);
    }

    /**
     * @inheritdoc
     * @return PublicationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PublicationQuery(get_called_class());
    }
    
    public function uploadDoc()
    {   
        $chemin = "uploads/".md5($this->creePar0->mail)."/publication-files/".md5($this->id)."/";
        
        $types = ["image", "video", "pdf"];
        $type_valide = false;

        if ( ! is_dir($chemin)) {
            mkdir($chemin, 0777, true);
        }
        if ($this->type_contenu == 1 && $this->champ_doc) {
            //validation
            foreach ($types as $type) {
                if (strpos($this->champ_doc->type, $type) !== false) {$type_valide = true; }
            }
            if ($type_valide) {
                $doc = new Document();
                $doc->fichier = $this->champ_doc;
                //$doc->type = $fichier->type;
                $doc->gallery = null;
                if (!(  $doc->upload($chemin)  )) {
                    $this->addError('champ_doc', 'Le document ne peut pas etre uploade');
                    $this->addError('type_contenu', 'Le document ne peut pas etre uploade');
                    return false;
                }
                $this->contenu_doc = $doc->id;
            }else {
                $this->addError('champ_doc', Yii::t("app", 'Wrong file type: ').$this->champ_doc->type);
                return false;
            }

            $this->offsetUnset('champ_doc');
            if ($this->save()) {
                return true;
            }
        }elseif ($this->type_contenu == 0) {
            //var_dump($this->type_contenu);
            //Yii::$app->end();
            return true;
        }elseif (!$this->champ_doc){
            $this->addError('champ_doc', Yii::t("app", 'Invalid file'));
        }
            
        //var_dump($this->champ_doc);
        //Yii::$app->end();
        return false;
    }
    
    public function uploadImage()
    {   
        $chemin = "uploads/".md5($this->creePar0->mail)."/publication-files/".md5($this->id)."/";

        if ( ! is_dir($chemin)) {
            mkdir($chemin, 0777, true);
        }
        
        if ($this->champ_image) {
            //var_dump($this->champ_image);
            //Yii::$app->end();
            $chemin_image = $chemin."logo.".$this->champ_image->extension;
            $this->champ_image->saveAs($chemin_image);
            $this->image = "/".$chemin_image;

            $this->offsetUnset('champ_image');    
        }else {
            if ($this->image == "") $this->image = $this->creePar0->logo;
        }
        if ($this->save()) {
            return true;
        }
            
        $this->addError('champ_image', 'Le fichier image ne peut pas etre uploade');
        return false;
    }
    
    public function beforeDelete() {
        if ($this->type_contenu) {
            $this->contenuDoc->delete();
            $chemin = $_SERVER['DOCUMENT_ROOT']."/uploads/".md5($this->creePar0->mail)."/publication-files/".md5($this->id);
            $chemin = \yii\helpers\FileHelper::normalizePath($chemin);
            if (parent::beforeDelete() && is_dir($chemin) ) {
                //unlink($chemin );
                //rmdir(dirname($chemin));
                \yii\helpers\FileHelper::removeDirectory($chemin);
                return true;
            }else {
                //var_dump($_SERVER['DOCUMENT_ROOT']);
                //Yii::$app->end(); 
            }
        }
        
        return false;
    }
    
    public function moveHtmlImages()
    {
        $cUser = Yii::$app->user->identity;
        $old_dir = '/uploads/'.md5($cUser->mail)."/publication-files/tmp/";
        $dir    = '/uploads/'.md5($cUser->mail)."/publication-files/".  md5($this->id)."/";
        
        if (is_dir($_SERVER['DOCUMENT_ROOT'].$old_dir)) {
            $fichiers = scandir($_SERVER['DOCUMENT_ROOT'].$old_dir);
            foreach ($fichiers as $fichier) {
                //rename(".".$old_dir.$fichier, ".".$dir.$fichier);
                if ($fichier!= "." && $fichier != "..") {

                    if (copy(".".$old_dir."".$fichier , ".".$dir."".$fichier)) {
                        unlink(".".$old_dir.$fichier);
                    }

                    str_replace(
                        $old_dir,
                        $dir,
                        $this->contenu_html
                    );
                }

            }
            $this->save();
        }
        
        
    }
    
    
}
