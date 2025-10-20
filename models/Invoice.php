<?php

namespace app\models;

use DateTime;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "invoice".
 *
 * @property int $id
 * @property string $dateinvoiced
 * @property string $duedate
 * @property int $duedays
 * @property string|null $paymentdate
 * @property string $nr_factura
 * @property string $partener
 * @property float|null $valoare_ron
 * @property float|null $suma_achitata_ron
 * @property float|null $sold_ron
 * @property float|null $valoare_eur
 * @property float|null $suma_achitata_eur
 * @property float|null $sold_eur
 * @property float|null $diferenta
 * @property string $moneda
 * @property string $is_customer
 * @property string|null $mentiuni
 * @property string|null $credit_note
 * @property int $created_at
 * @property int $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int |null $partner_id
 * @property Partner $partner
 * @property User $createdBy
 * @property User $updatedBy
 * @property int $paymentterm
 * @property int | null $paid_status
 */
class Invoice extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['paymentdate', 'mentiuni', 'credit_note', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['duedays'], 'default', 'value' => 0],
            [['diferenta'], 'default', 'value' => 0.00],
            [['moneda'], 'default', 'value' => ''],
            [['is_customer'], 'default', 'value' => 'Y'],
            [['dateinvoiced', 'duedate', 'nr_factura', 'partner_id'], 'required'],
            [['dateinvoiced', 'duedate', 'paymentdate'], 'safe'],
            [['duedays', 'created_at', 'updated_at', 'created_by', 'updated_by','partner_id'], 'integer'],
            [['valoare_ron', 'suma_achitata_ron', 'sold_ron', 'valoare_eur', 'suma_achitata_eur', 'sold_eur', 'diferenta'], 'number'],
            [['nr_factura'], 'string', 'max' => 50],
            [['partener'], 'string', 'max' => 100],
            [['moneda'], 'string', 'max' => 3],
            [['is_customer'], 'string', 'max' => 1],
            [['mentiuni', 'credit_note'], 'string', 'max' => 4000],
            // [['nr_factura'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
             [['paymentterm'], 'default', 'value' => null],
             [['paid_status'], 'default', 'value' => null],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dateinvoiced' => 'Data Factura',
            'duedate' => 'Data Scadenta',
            'duedays' => 'Duedays',
            'paymentdate' => 'Data Incasare',
            'nr_factura' => 'Nr Factura',
            'partener' => 'Partener',
            'valoare_ron' => 'Valoare Ron',
            'suma_achitata_ron' => 'Suma Achitata Ron',
            'sold_ron' => 'Sold Ron',
            'valoare_eur' => 'Valoare Eur',
            'suma_achitata_eur' => 'Suma Achitata Eur',
            'sold_eur' => 'Sold Eur',
            'diferenta' => 'Diferenta',
            'moneda' => 'Moneda',
            'is_customer' => 'Is Customer',
            'mentiuni' => 'Mentiuni',
            'credit_note' => 'Credit Note',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'partner_id'=>'Partener',
            'paymentterm'=>'Termen Plata',
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
     * @return InvoiceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InvoiceQuery(get_called_class());
    }

       public function beforeSave($insert){
        if(!parent::beforeSave($insert)){
            
            return false;
        }        
        $this->calculateDueDays();    
        $mPartner = $this->getPartner()->one();        
        
        return true;
    }

    public function calculateDueDays()
    {
        $inv_date = new DateTime($this->dateinvoiced);
        $due_date = new DateTime($this->duedate);
        $diff = $due_date->diff($inv_date);
        $this->duedays = $diff->days;    
        $this->partener = $this->partner->name;    
    }
    public function calculateSold($currency)
    {
        if($currency==='RON')
        {
            $this->sold_ron = $this->valoare_ron - $this->suma_achitata_ron;
        }
        if($currency==='EUR')
        {
            $this->sold_eur = $this->valoare_eur - $this->suma_achitata_eur;
        }
    }
      public function getPartner()
    {
        return $this->hasOne(Partner::class, ['id' => 'partner_id']);
    }
       public static function getPaymentTerm() {
        return [5=>'5 Zile',
                15=>'15 Zile',
                30=>'30 Zile',
                45=>'45 Zile',
                60=>'60 Zile',
                90=>'90 Zile',    
            ];
    }
     public static function getPaidStatus(){
        return [0=>'Partial',
            1=>'Total',];
    }

    public function getPaid(){
        return $this->getPaidStatus()[$this->paid_status]??'';
    }

}
