<?php

namespace app\models;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use app\models\Vehicle;
/**
 * This is the model class for table "transport_order".
 *
 * @property int $id
 * @property string $documentno
 * @property string $dateordered
 * @property int $partner_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property User $createdBy
 * @property Partner $partner
 * @property User $updatedBy
 */
class TransportOrder extends \yii\db\ActiveRecord
{

    const STATUS_NEALOCAT=0;
    const STATUS_ALOCAT=1;
    const STATUS_FINALIZAT=2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transport_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [            
           /* [['documentno', 'dateordered', 'partner_id'], 'unique',
            'targetAttribute'=>['documentno', 'dateordered','partner_id'],'message'=>'Numar comanda, Data Comanda si Partener trebuie sa fie unice'], */
            [['documentno'], 'safe'],
            [['dateordered'], 'date', 'format' => 'php:Y-m-d'], // validates proper date
            [['dateordered'], 'default', 'value' => null],       // default NULL
            [['partner_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['documentno'], 'string', 'max' => 2000],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['partner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Partner::class, 'targetAttribute' => ['partner_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
            [['status'],'integer'],
            [['status'],'default','value'=>self::STATUS_NEALOCAT],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'documentno' => 'Nr. Comanda',
            'dateordered' => 'Data Comanda',
            'partner_id' => 'Client',
            'created_at' => 'Data Creare',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'status'=>'Stare',
        ];
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class,
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Partner]].
     *
     * @return \yii\db\ActiveQuery|PartnerQuery
     */
    public function getPartner()
    {
        return $this->hasOne(Partner::class, ['id' => 'partner_id']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    /**
     * {@inheritdoc}
     * @return TransportOrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransportOrderQuery(get_called_class());
    }
    public function beforeSave($insert){
     
      if(parent::beforeSave($insert)){                       
        //  $this->dateordered =Yii::$app->formatter->asDatetime($this->dateordered,'php:Y-m-d');         
               
        return true;     
    }            
        
        return false;
    }

    public function getFormatedDateOrdered(){
        return Yii::$app->formatter->asDatetime($this->dateordered,'php:d.m.Y');    
    }

       public static function getStatusList() {
        return[
            self::STATUS_ALOCAT=>'Alocata',
            self::STATUS_NEALOCAT=> 'Nealocata',
            self::STATUS_FINALIZAT=> 'Finalizata',
        ];
    }
     public function getStatusName(){
        return self::getStatusList()[$this->status]??'Necunoscut';
    }
    public function beforeDelete(){
        Vehicle::updateAll(['transport_order_id'=>null,'status'=>0],'transport_order_id='.$this->id);
        return parent::beforeDelete();
    }
}
