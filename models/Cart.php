<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cart".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CartItem[] $cartItems
 * @property User $user
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cart';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CartItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCartItems()
{
    return $this->hasMany(CartItem::class, ['cart_id' => 'id']);
}



    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getTotalQuantity()
    {
        return array_sum(array_map(function ($item) {
            return $item->quantity;
        }, $this->cartItems));
    }

    public function getTotalPrice()
{
    $total = 0;

    foreach ($this->cartItems as $cartItem) {
        switch ($cartItem->item_type) {
            case 'product':
                $item = Product::findOne($cartItem->item_id);
                break;
            case 'work':
                $item = Work::findOne($cartItem->item_id);
                break;
            case 'service':
                $item = Service::findOne($cartItem->item_id);
                break;
            default:
                $item = null;
        }

        if ($item) {
            $total += $item->price * $cartItem->quantity;
        }
    }

    return $total;
}


}
