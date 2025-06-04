<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_item".
 *
 * @property int $id
 * @property int $order_id
 * @property string $item_type
 * @property int $item_id
 * @property int $quantity
 * @property int $price
 *
 * @property Order $order
 */
class OrderItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'item_type', 'item_id', 'quantity', 'price'], 'required'],
            [['order_id', 'item_id', 'quantity', 'price'], 'integer'],
            [['item_type'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::class, 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'item_type' => 'Item Type',
            'item_id' => 'Item ID',
            'quantity' => 'Quantity',
            'price' => 'Price',
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }


    //////////////////////////////////////////////////////////////////////////////////////////////

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'item_id']);
    }

    public function getWork()
    {
        return $this->hasOne(Work::class, ['id' => 'item_id']);
    }

    public function getService()
    {
        return $this->hasOne(Service::class, ['id' => 'item_id']);
    }

    /**
     * Возвращает привязанную модель — Product, Work или Service
     *
     * @return Product|Work|Service|null
     */
    public function getItemModel()
    {
        switch ($this->item_type) {
            case 'product':
                return $this->product;
            case 'work':
                return $this->work;
            case 'service':
                return $this->service;
            default:
                return null;
        }
    }


}
