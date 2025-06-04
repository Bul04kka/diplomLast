<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use app\models\Cart;
use app\models\CartItem;
use app\models\Order;
use app\models\ItemOrder;
use app\models\OrderItem;

class CartController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'view','add','remove','clear','update-quantity','count',
                    'recommendation',
                    'order','order-success',
                    'orders','view-order'
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionAdd($type, $id, $quantity = 1)
    {
        $userId = Yii::$app->user->id;
        $cart = Cart::findOne(['user_id' => $userId]) ?: new Cart(['user_id' => $userId]);
        $cart->save(false);

        $cartItem = CartItem::findOne([
            'cart_id' => $cart->id,
            'item_type' => $type,
            'item_id' => $id,
        ]);

        if ($cartItem) {
            if ($type === 'product') {
                $cartItem->quantity += $quantity;
                $cartItem->save(false);
                return $this->asJson(['success' => true]);
            }
            return $this->asJson([
                'success' => false,
                'message' => 'Вы уже добавили эту работу или услугу в корзину.'
            ]);
        }

        $cartItem = new CartItem([
            'cart_id' => $cart->id,
            'item_type' => $type,
            'item_id' => $id,
            'quantity' => $quantity,
        ]);
        $cartItem->save(false);

        return $this->asJson(['success' => true]);
    }

    public function actionView()
    {
        $cart = Cart::findOne(['user_id' => Yii::$app->user->id]);
        return $this->renderPartial('view', ['cart' => $cart]);
    }

    public function actionClear()
    {
        $cart = Cart::findOne(['user_id' => Yii::$app->user->id]);
        if ($cart) {
            CartItem::deleteAll(['cart_id' => $cart->id]);
        }
        return Yii::$app->request->isAjax
            ? $this->asJson(['success' => true])
            : $this->redirect(['view']);
    }

    public function actionRemove($id)
    {
        $item = CartItem::findOne($id);
        if ($item && $item->cart->user_id === Yii::$app->user->id) {
            $item->delete();
        }
        return Yii::$app->request->isAjax
            ? $this->asJson(['success' => true])
            : $this->redirect(['view']);
    }

    public function actionUpdateQuantity($id, $quantity)
    {
        $item = CartItem::findOne($id);
        if ($item && $item->cart->user_id === Yii::$app->user->id) {
            $item->quantity = max(1, (int)$quantity);
            $item->save(false);
        }
        return Yii::$app->request->isAjax
            ? $this->asJson(['success' => true])
            : $this->redirect(['view']);
    }

    public function actionCount()
    {
        $cart = Cart::findOne(['user_id' => Yii::$app->user->id]);
        $count = $cart ? $cart->getTotalQuantity() : 0;
        return $this->asJson(['count' => $count]);
    }

    public function actionRecommendation()
    {
        $userId = Yii::$app->user->id;
        $cart = Cart::findOne(['user_id' => $userId]) ?: new Cart(['user_id' => $userId]);
        $cart->save(false);

        CartItem::deleteAll([
            'cart_id'   => $cart->id,
            'item_type' => 'product',
            'item_id'   => 27,
        ]);
        CartItem::deleteAll([
            'cart_id'   => $cart->id,
            'item_type' => 'work',
            'item_id'   => 14,
        ]);

        $data = Yii::$app->request->post();
        if (!empty($data['enable_curtains']) && !empty($data['curtains_quantity'])) {
            $countMod = ceil((int)$data['curtains_quantity'] / 2);
            $this->addItemToCart('product', 27, $countMod);
            $this->addItemToCart('work',    14, $countMod);
        }

        return Yii::$app->request->isAjax
            ? $this->asJson(['success' => true])
            : $this->redirect(Yii::$app->request->referrer ?: ['/account/service/index']);
    }

    private function addItemToCart($type, $id, $quantity)
    {
        $userId = Yii::$app->user->id;
        $cart = Cart::findOne(['user_id' => $userId]) ?: new Cart(['user_id' => $userId]);
        $cart->save(false);

        $item = CartItem::findOne([
            'cart_id'   => $cart->id,
            'item_type' => $type,
            'item_id'   => $id,
        ]);

        if ($item && $type === 'product') {
            $item->quantity += $quantity;
        } elseif (!$item) {
            $item = new CartItem([
                'cart_id'   => $cart->id,
                'item_type' => $type,
                'item_id'   => $id,
                'quantity'  => $quantity,
            ]);
        }
        $item->save(false);
    }

    public function actionOrder()
    {
        $userId = Yii::$app->user->id;
        $cart   = Cart::findOne(['user_id' => $userId]);
        if (!$cart || empty($cart->cartItems)) {
            Yii::$app->session->setFlash('error', 'Ваша корзина пуста.');
            return $this->redirect(['view']);
        }

        // Расчёт общей стоимости
        $total = 0;
        foreach ($cart->cartItems as $ci) {
            if ($ci->item_type === 'product') {
                $m = \app\models\Product::findOne($ci->item_id);
            } else {
                $m = \app\models\Work::findOne($ci->item_id);
            }
            $price = $m->price ?? 0;
            $total += $price * $ci->quantity;
        }

        // Создание заказа
        $order = new Order();
        $order->user_id     = $userId;
        $order->total_price = $total;
        $order->save(false);

        // Копирование позиций
        foreach ($cart->cartItems as $ci) {
            $price = ($ci->item_type === 'product')
                ? (\app\models\Product::findOne($ci->item_id)->price ?? 0)
                : (\app\models\Work::findOne($ci->item_id)->price ?? 0);
            $oi = new OrderItem();
            $oi->order_id  = $order->id;
            $oi->item_type = $ci->item_type;
            $oi->item_id   = $ci->item_id;
            $oi->quantity  = $ci->quantity;
            $oi->price     = $price;
            $oi->save(false);
        }

        CartItem::deleteAll(['cart_id' => $cart->id]);
        return $this->redirect(['order-success', 'id' => $order->id]);
    }

    public function actionOrderSuccess($id)
    {
        $order = Order::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);
        if (!$order) {
            throw new NotFoundHttpException('Заказ не найден');
        }
        return $this->render('order-success', ['order' => $order]);
    }

    /**
     * Список заказов текущего пользователя
     */
    public function actionOrders()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Order::find()
                ->where(['user_id' => Yii::$app->user->id])
                ->orderBy(['created_at' => SORT_DESC]),
            'pagination' => ['pageSize' => 10],
        ]);
        return $this->render('orders', ['dataProvider' => $dataProvider]);
    }

    /**
     * Просмотр отдельного заказа
     */
    public function actionViewOrder($id)
    {
        $order = Order::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);
        if (!$order) {
            throw new NotFoundHttpException('Заказ не найден');
        }
        return $this->render('view-order', ['order' => $order]);
    }
}
