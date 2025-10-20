<?php

namespace app\models;

use DateTime;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "payment".
 *
 * @property int $id
 * @property string $is_receipt
 * @property string $dateinvoiced
 * @property string $duedate
 * @property int $duedays
 * @property string $nr_cmd_trs
 * @property string $nr_factura
 * @property string $partener
 * @property float|null $valoare_ron
 * @property float|null $suma_achitata_ron
 * @property float|null $sold_ron
 * @property float|null $valoare_eur
 * @property float|null $suma_achitata_eur
 * @property float|null $sold_eur
 * @property string|null $ron
 * @property string|null $eur
 * @property string|null $paymentdate
 * @property string|null $bank
 * @property string|null $mentiuni
 * @property int $created_at
 * @property int $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $paid_status
 * @property User $createdBy
 * @property User $updatedBy
 * @property int $paymentterm
 */
class Payment extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['paymentdate', 'mentiuni', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['is_receipt'], 'default', 'value' => 'N'],
            [['duedays'], 'default', 'value' => 0],
            [['sold_eur'], 'default', 'value' => 0.00],
            [['ron'], 'default', 'value' => ''],
            [['eur'], 'default', 'value' => ''],
            [['bank'], 'default', 'value' => ''],
            [['dateinvoiced', 'duedate', 'nr_cmd_trs', 'nr_factura', 'partener'], 'required'],
            [['dateinvoiced', 'duedate', 'paymentdate'], 'safe'],
            [['duedays', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['valoare_ron', 'suma_achitata_ron', 'sold_ron', 'valoare_eur', 'suma_achitata_eur', 'sold_eur'], 'number'],
            [['mentiuni'], 'string'],
            [['is_receipt'], 'string', 'max' => 1],
            [['nr_cmd_trs', 'nr_factura'], 'string', 'max' => 50],
            [['partener', 'bank'], 'string', 'max' => 100],
            [['ron', 'eur'], 'string', 'max' => 50],
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
            'is_receipt' => 'Incasare',
            'dateinvoiced' => 'Data Factura',
            'duedate' => 'Data Scandenta',
            'duedays' => 'Zile scadenta',
            'nr_cmd_trs' => 'Nr Cmd Trs',
            'nr_factura' => 'Nr Factura',
            'partener' => 'Partener',
            'valoare_ron' => 'Valoare RON',
            'suma_achitata_ron' => 'Suma Achitata RON',
            'sold_ron' => 'Sold RON',
            'valoare_eur' => 'Valoare EUR',
            'suma_achitata_eur' => 'Suma Achitata EUR',
            'sold_eur' => 'Sold EUR',
            'ron' => 'Cont RON',
            'eur' => 'Cont EUR',
            'paymentdate' => 'Data Achitarii',
            'bank' => 'Banca',
            'mentiuni' => 'Mentiuni',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
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
     * @return PaymentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PaymentQuery(get_called_class());
    }

     public function beforeSave($insert){
        if(!parent::beforeSave($insert)){
            
            return false;
        }        
        $this->calculateDueDays();    
        return true;
    }

    public function calculateDueDays()
    {
        $inv_date = new DateTime($this->dateinvoiced);
        $due_date = new DateTime($this->duedate);
        $diff = $due_date->diff($inv_date);
        $this->duedays = $diff->days;
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
