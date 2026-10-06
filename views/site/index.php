<?php

/** @var yii\web\View $this */
use yii\helpers\Html;
$this->title = Yii::$app->name;
?>
<div class="site-index">

   <div class="jumbotron text-center bg-transparent">
      <h1 class="display-4"><i class="fa fa-road"></i> <?= $this->title ?></h1>
      <div class="alert alert-info" role="alert">
    
      <?php if (Yii::$app->user->isGuest): ?>
           <p>Pentru a utiliza aceasta aplicatie trebuie sa va autentificati !</p>
		   <?= Html::a('Autentificare', ['/site/login'], ['class' => 'btn btn-lg btn-success']) ?>      
      <?php else: ?>
           <p>Sunteti deja autentificat !</p>
      <?= Html::a('Deconectare',['/site/logout'],['class'=>'btn btn-lg btn-danger','data'=>['method'=>'post']])?>
      <?php endif; ?>
</div>
   </div>   
   </div>
   