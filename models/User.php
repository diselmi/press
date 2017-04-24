<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $nom
 * @property string $prenom
 * @property string $mail
 * @property string $login
 * @property string $pass
 * @property integer $lang
 * @property string $photo
 * @property integer $role
 * @property integer $fonction
 * @property string $logo
 * @property string $adresse
 * @property string $domaines
 * @property string $couleur_interface
 * @property integer $superieur
 * @property integer $cree_par
 * 
 * @property Abonnement $abonnement0
 * @property Document[] $documents
 * @property Facture[] $factures
 * @property Message[] $messages
 * @property Message[] $messages0
 * @property Nouveaute[] $nouveautes
 * @property Privilege[] $privileges
 * @property Salle[] $salles
 * @property Service[] $services
 * @property Langue $lang0
 * @property Role $role0
 * @property Fonction $fonction0
 * @property User $superieur0
 * @property User $creePar0
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    use \mootensai\relation\RelationTrait;
    
    /**
     * @var UploadedFile
     */
    public $imagePhoto;
    /**
     * @var UploadedFile
     */
    public $imageLogo;
    
    public $role_type;
    
    public $identity;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nom', 'mail', 'role'], 'required'],
            [['role', 'lang', 'fonction'], 'integer'],
            [['nom', 'prenom', 'mail', 'login', 'pass'], 'string', 'max' => 32],
            [['adresse'], 'string', 'max' => 64],
            [['domaines'], 'string', 'max' => 64],
            [['mail'], 'unique'],
            [['couleur_interface'], 'string', 'min' => 7, 'max' => 7],
            [['imagePhoto'], 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['imageLogo'], 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            //[['abonnement'], 'exist', 'skipOnError' => true, 'targetClass' => Abonnement::className(), 'targetAttribute' => ['client' => 'id']],
            [['cree_par'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['cree_par' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nom' => Yii::t('app', 'Nom'),
            'prenom' => Yii::t('app', 'Prenom'),
            'mail' => Yii::t('app', 'Mail'),
            'login' => Yii::t('app', 'Login'),
            'pass' => Yii::t('app', 'Pass'),
            'lang' => Yii::t('app', 'Lang'),
            'photo' => Yii::t('app', 'Photo'),
            'role' => Yii::t('app', 'Role'),
            'fonction' => Yii::t('app', 'Fonction'),
            'logo' => Yii::t('app', 'Logo'),
            'adresse' => Yii::t('app', 'Adresse'),
            'domaines' => Yii::t('app', 'Domaines'),
            'couleur_interface' => Yii::t('app', 'Couleur Interface'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAbonnement0()
    {
        return $this->hasOne(Abonnement::className(), ['client' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['ajoute_par' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFactures()
    {
        return $this->hasMany(Facture::className(), ['client' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['expediteur' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages0()
    {
        return $this->hasMany(Message::className(), ['destinataire' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNouveautes()
    {
        return $this->hasMany(Nouveaute::className(), ['cree_par' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrivileges()
    {
        return $this->hasMany(Privilege::className(), ['cree_par' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalles()
    {
        return $this->hasMany(Salle::className(), ['cree_par' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServices()
    {
        return $this->hasMany(Service::className(), ['cree_par' => 'id']);
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
    public function getRole0()
    {
        return $this->hasOne(Role::className(), ['id' => 'role']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFonction0()
    {
        return $this->hasOne(Fonction::className(), ['id' => 'fonction']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSuperieur0()
    {
        return $this->hasOne(User::className(), ['id' => 'superieur']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreePar0()
    {
        return $this->hasOne(User::className(), ['id' => 'cree_par']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAjouts()
    {
        return $this->hasMany(User::className(), ['cree_par' => 'id']);
    }
    
    public function getNomPrenom()
    {
        return $this->nom." ".$this->prenom;
    }
    
    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }
    
    
    public function upload()
    {
        if ($this->validate()) {
            $chemin = "uploads/".md5($this->mail);
  
            if ( ! is_dir($chemin."/")) {
                mkdir($chemin."/");
            }
            
            if ($this->imagePhoto) {
                $cheminPhoto = $chemin."/photo.".$this->imagePhoto->extension;
                $this->imagePhoto->saveAs($cheminPhoto);
                $this->photo = "/".$cheminPhoto;
                $this->offsetUnset('imagePhoto');
            }else {
                $this->photo = "/images/profile_holder_m.jpg";
            }
            if ($this->imageLogo) {
                $cheminLogo = $chemin."/logo.".$this->imageLogo->extension;
                $this->imageLogo->saveAs($cheminLogo);
                $this->logo = "/".$cheminLogo;
                $this->offsetUnset('imageLogo');
            }else {
                $this->logo = "/images/enterprise_holder.png";
            }
            
            $this->offsetUnset('role_type');
            $this->save();
            return true;
            
            
        } else {
            //var_dump($this->errors);
            //Yii::trace($this->errors);
            return false;
        }
    }
    
    
    ////////////////////
    // IdentityInterface
    ////////////////////
    
    public function validerPass($pass)
    {
        return ($this->pass == $pass);
    }

    public function getAuthKey() {
        return "AuthKey";
    }

    public function getId() {
        return $this->id;
    }

    public function validateAuthKey($authKey) {
        return true;
    }

    public static function findIdentity($id) {
        return User::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        return User::find()->andFilterWhere([
            'id' => $token,
        ]);
    }
    
    /***************/
    
    public function switchWith($id) {
        if ($id) {
            Yii::$app->user->switchIdentity(User::findIdentity($id));
        }
    }
    
    
    ////////////////////
    // Autorisations
    ////////////////////

    
    public function estCreateur($user_id, $model_name, $model_id){
        return 1;
    }

}
