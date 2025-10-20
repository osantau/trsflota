<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use app\models\Payment;

/** @var yii\web\View $this */
/** @var app\models\Payment $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="payment-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'w-75 mx-auto p-4 bg-light rounded shadow-sm']]); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->errorSummary($model) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'nr_factura')->textInput(['maxlength' => true,'autofocus'=>true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'dateinvoiced')->input('date') ?>
        </div>       
    </div>
    <div class="row">
 <div class="col-md-4">
            <?= $form->field($model, 'duedate')->input('date') ?>
        </div>
         <div class="col-md-4">
           <?= $form->field($model, 'paymentterm')->dropDownList(Payment::getPaymentTerm() 
           ,['prompt'=>''])->label('Termen Incasare') 
            ?>
        </div>
         <div class="col-md-4">
           <?= $form->field($model, 'paid_status')->dropDownList(Payment::getPaidStatus() 
           ,['prompt'=>''])->label('Stare Achitare') 
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'nr_cmd_trs')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'partener')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'bank')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'valoare_ron')->textInput(['type' => 'number', 'step' => '0.01', 'maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'suma_achitata_ron')->textInput(['type' => 'number', 'step' => '0.01', 'maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'sold_ron')->textInput(['type' => 'number', 'step' => '0.01', 'maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'valoare_eur')->textInput(['type' => 'number', 'step' => '0.01', 'maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'suma_achitata_eur')->textInput(['type' => 'number', 'step' => '0.01', 'maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'sold_eur')->textInput(['type' => 'number', 'step' => '0.01', 'maxlength' => true]) ?>
        </div>
    </div>
     <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'ron')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'eur')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'paymentdate')->input('date') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'mentiuni')->textarea(['rows' => 2]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Salveaza', ['class' => 'btn btn-success px-4']) ?>
         <?= Html::a('Renunta',['/payment'], ['class' => 'btn btn-primary']) ?>
    </div>
    

    <?php ActiveForm::end(); ?>

</div>
<?php
$js = <<<JS
$('#payment-paymentterm').on('change', function() {
    var days = parseInt($(this).val());
    if (!isNaN(days)) {
        var dateInvoiced = $('#payment-dateinvoiced').val();
        if (dateInvoiced) {
            var dueDate = new Date(dateInvoiced);
            dueDate.setDate(dueDate.getDate() + days);
            // format YYYY-MM-DD
            var month = ('0' + (dueDate.getMonth()+1)).slice(-2);
            var day = ('0' + dueDate.getDate()).slice(-2);
            var formatted = dueDate.getFullYear() + '-' + month + '-' + day;
            $('#payment-duedate').val(formatted);
        }
    } else {
        $('#payment-duedate').val(null); 
    }
});
JS;
$this->registerJs($js);
?>