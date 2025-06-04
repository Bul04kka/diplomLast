<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cart_item".
 *
 * @property int $id
 * @property int $cart_id
 * @property string $item_type
 * @property int $item_id
 * @property int $quantity
 *
 * @property Cart $cart
 * @property Product|null $product
 * @property Work|null $work
 * @property Service|null $service
 */
class CartItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cart_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cart_id', 'item_type', 'item_id', 'quantity'], 'required'],
            [['cart_id', 'item_id', 'quantity'], 'integer'],
            [['item_type'], 'string', 'max' => 255],
            [['cart_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cart::class, 'targetAttribute' => ['cart_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'cart_id'   => 'Cart ID',
            'item_type' => 'Item Type',
            'item_id'   => 'Item ID',
            'quantity'  => 'Quantity',
        ];
    }

    /**
     * Gets query for [[Cart]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCart()
    {
        return $this->hasOne(Cart::class, ['id' => 'cart_id']);
    }

    /**
     * Связь на продукт (если item_type = 'product').
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'item_id']);
    }

    /**
     * Связь на работу (если item_type = 'work').
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWork()
    {
        return $this->hasOne(Work::class, ['id' => 'item_id']);
    }

    /**
     * Связь на услугу (если item_type = 'service').
     *
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(Service::class, ['id' => 'item_id']);
    }

    /**
     * Возвращает конкретную модель элемента корзины:
     * Product, Work или Service.
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
