<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use app\models\User;
use kartik\password\PasswordInput;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="user-form">
   
    <?php $form = ActiveForm::begin(); ?>
 <?= $form->errorSummary($model) ?>
    <?= $form->field($model, 'username')->textInput(['maxlength' => true,'autofocus'=>true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->widget(PasswordInput::class, [
    'pluginOptions' => [
        'showMeter' => true, // show strength meter
        'toggleMask' => true, // show/hide password
        'strengthMeterOptions' => [
            'minLength' => 8,
            'requireLowercase' => true,
            'requireUppercase' => true,
            'requireDigit' => true,
            'requireSpecial' => true,
        ],
    ]
]); ?>  

    <?= $form->field($model, 'is_admin')->dropDownList(User::getAdminList(),['label'=>"Administrator?"] )?>

    <div class="form-group">
        <?= Html::submitButton('Salveaza', ['class' => 'btn btn-success']) ?>
		<?= Html::a('Renunta',['/user'], ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
