<?php
namespace app\modules\account\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use app\models\Order;
use app\models\OrderItem;
use app\models\Cart;
use app\models\CartItem;

class OrderController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only'  => ['index','view','create'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Список заказов текущего пользователя
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Order::find()
                ->where(['user_id' => Yii::$app->user->id])
                ->orderBy(['created_at' => SORT_DESC]),
            'pagination' => ['pageSize' => 10],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Просмотр одного заказа
     */
    public function actionView($id)
    {
        $order = Order::findOne([
            'id'      => $id,
            'user_id' => Yii::$app->user->id,
        ]);
        if (!$order) {
            throw new NotFoundHttpException('Заказ не найден.');
        }

        return $this->render('view', [
            'order' => $order,
        ]);
    }

    /**
     * Создать заказ сразу из корзины и очистить её
     */
     public function actionCreate()
    {
        $userId = Yii::$app->user->id;
        $cart   = Cart::findOne(['user_id' => $userId]);

        if (!$cart || empty($cart->cartItems)) {
            Yii::$app->session->setFlash('error', 'Ваша корзина пуста.');
            return $this->redirect(['/account/cart/view']);
        }

        // 1) Подсчёт общей суммы
        $totalPrice = 0;
        foreach ($cart->cartItems as $ci) {
            $model = $ci->itemModel;
            $price = $model->price ?? 0;
            $totalPrice += $price * $ci->quantity;
        }

        // 2) Создаём заказ и сразу присваиваем статус
        $order = new Order();
        $order->user_id     = $userId;
        $order->total_price = $totalPrice;   
        $order->status_id   = 1;
        $order->save(false);

        // 3) Копируем позиции
        foreach ($cart->cartItems as $ci) {
            $oi = new OrderItem([
                'order_id'  => $order->id,
                'item_type' => $ci->item_type,
                'item_id'   => $ci->item_id,
                'quantity'  => $ci->quantity,
                'price'     => $ci->itemModel->price ?? 0,
            ]);
            $oi->save(false);
        }

        // 4) Очищаем корзину
        CartItem::deleteAll(['cart_id' => $cart->id]);

        // 5) Редирект на просмотр нового заказа
        return $this->redirect(['view', 'id' => $order->id]);
    }
}
