<?php

namespace app\controllers;

use app\models\TransportOrder;
use app\models\TransportOrderSearch;
use app\models\Vehicle;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Yii;
use yii\web\Response;
use yii\helpers\ArrayHelper;
/**
 * TransportOrderController implements the CRUD actions for TransportOrder model.
 */
class TransportOrderController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ],
             ['access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        // allow only the specific user (by username)
                        'allow' => true,
                        'roles' => ['@'], // logged-in users                        
                    ],
                ],
            ],],
        );
    }

    /**
     * Lists all TransportOrder models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TransportOrderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TransportOrder model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TransportOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new TransportOrder();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TransportOrder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($model!=null)
        {
            if($model->status===0)
            {
                $vehicle = Vehicle::findOne(['transport_order_id'=>$model->id]);
                if($vehicle!=null) {
                $vehicle->status=0;
                $vehicle->transport_order_id=null;
                $vehicle->start_date=null;
                $vehicle->end_date=null;
                $vehicle->exp_adr_start=null;
                $vehicle->exp_adr_end=null;                
                $vehicle->imp_adr_start=null;
                $vehicle->imp_adr_end=null;                
                $vehicle->info=null;
                $vehicle->save();
                }
            } 
        }
        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TransportOrder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TransportOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return TransportOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TransportOrder::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionOrderList()
    {
         $query = TransportOrder::find()
        ->select(['id', 'text' => 'documentno'])        
        ->where(['status'=>0])
        ->asArray()
        ->all();  
        return json_encode($query);    
    }

   public function actionSummary($id, $cId) {
    Yii::$app->response->format = Response::FORMAT_JSON;
          $model=$this->findModel($cId);        
      $content=   $this->renderPartial('_popover', [
        'model' => $model,
    ]);

    return ['content'=>$content];
}

public function actionInfo($id, $cId)
{
     Yii::$app->response->format = Response::FORMAT_JSON;    
        $order = TransportOrder::findOne($cId)?? new TransportOrder();
        return $this->renderAjax('info', [
        'c_id' => $cId,'v_id'=>$id,'documentno'=>$order->documentno,
    ]);
}

public function actionInfoAjax()
{
    Yii::$app->response->format = Response::FORMAT_JSON;
    $partnerId = null;       // or some integer
    $userId = Yii::$app->user->id;
    $cId =Yii::$app->request->post('id'); 
    $v_id = Yii::$app->request->post('v_id');
    $documentno = Yii::$app->request->post('documentno');
    $vehicle = Vehicle::findOne($v_id);
    $remove_cmd=Yii::$app->request->post('remove_cmd');    
    if($remove_cmd==1)
        {
          $vehicle->status=0;
          $vehicle->transport_order_id=null;
          $vehicle->save();
            return ['success'=>true];
        } 
        
        $transaction = Yii::$app->db->beginTransaction();
try {
    // Create order
    $order = new TransportOrder([
        'documentno' => $documentno,
        'partner_id' => $partnerId,        
        'status' => 0,
        'created_by' => $userId,
        'created_at' => time(),
        'updated_at' => time(),
        'regno'=>$vehicle->regno,
    ]);
    $order->save(false);

    // Update vehicle
    $vehicle->transport_order_id = $order->id;
    $vehicle->status=1;
    $vehicle->save(false);

    $transaction->commit();
    return ['success' => true];
} catch (\Throwable $e) {
    $transaction->rollBack();
    return ['success' => false, 'error' => $e->getMessage()];
}   
}

}
