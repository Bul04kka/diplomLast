<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use app\models\Order;
use app\models\Status;

class OrderController extends Controller
{
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>\yii\filters\AccessControl::class,
                'only'=>['index','view','approve','reject'],
                'rules'=>[
                    [
                        'allow'=>true,
                        'roles'=>['@'],        // здесь можно добавить проверку роли 'admin'
                    ],
                ],
            ],
        ];
    }

    // Список всех заказов
    public function actionIndex()
    {
        $dp = new ActiveDataProvider([
            'query'=>Order::find()
                ->with(['user','status'])
                ->orderBy(['created_at'=>SORT_DESC]),
            'pagination'=>['pageSize'=>20],
        ]);

        return $this->render('index', [
            'dataProvider'=>$dp,
        ]);
    }

    // Детальный просмотр
    public function actionView($id)
{
    $order = Order::find()
        ->with([
            'user',
            'status',
            'orderItems.product',
            'orderItems.work',
            'orderItems.service',
        ])
        ->where(['id' => $id])
        ->one();

    if (!$order) {
        throw new NotFoundHttpException('Заказ не найден');
    }

    return $this->render('view', ['order' => $order]);
}


    // Утвердить заказ (status_id = 2)
    public function actionApprove($id)
    {
        return $this->changeStatus($id, 2, 'Заказ успешно утверждён.');
    }

    // Отклонить заказ (status_id = 3)
    public function actionReject($id)
    {
        return $this->changeStatus($id, 3, 'Заказ отклонён.');
    }

    // Вспомогательный метод
    protected function changeStatus($id, $newStatusId, $flash)
    {
        $order = Order::findOne($id);
        if (!$order) throw new NotFoundHttpException;
        $order->status_id = $newStatusId;
        $order->save(false);
        Yii::$app->session->setFlash('success', $flash);
        return $this->redirect(['view','id'=>$order->id]);
    }
}
