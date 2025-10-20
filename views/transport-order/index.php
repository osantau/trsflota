<?php

use app\models\TransportOrder;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\TransportOrderSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Comenzi';
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="transport-order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php //echo Html::a('Adauga Comanda', ['create'], ['class' => 'btn btn-success']); ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'documentno',
           // ['attribute'=>'dateordered','format' => ['datetime', 'php:d.m.Y']],
          //  ['attribute'=>'partnerName','value'=> 'partner.name','label'=>'Client'],
            ['attribute' => 'created_at', 'format' => ['datetime', 'php:d.m.Y H:i']],
         /*   ['attribute'=>'status',
             'value'=>function($model){
               
                return $model->getStatusName();
             },
             'filter'=>[TransportOrder::getStatusList()],
            ], */
            //'updated_at',
            //'created_by',
            //'updated_by',
           /* [
                'header'=>'Actiuni',
                'template'=>'{update} {delete}',
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, TransportOrder $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],*/
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
