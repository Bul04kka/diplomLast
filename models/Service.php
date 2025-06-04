<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "service".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $price
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ServiceProduct[] $serviceProducts
 * @property ServiceWork[] $serviceWorks
 */
class Service extends \yii\db\ActiveRecord
{
    public $product_ids = [];//для создания услуг
    public $work_ids = [];
    public $selected_product_id;
    public $selected_product_quantity;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['description'], 'string'],
            [['price'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['product_ids', 'work_ids'], 'safe'],
            [['selected_product_id', 'selected_product_quantity'], 'safe'],
            [['price'], 'safe'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Номер в системе',
            'name' => 'Наименование',
            'description' => 'Описание',
            'price' => 'Стоимость',
            'created_at' => 'Создана',
            'updated_at' => 'Обновлена',
        ];
    }

    /**
     * Gets query for [[ServiceProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getServiceProducts()
    {
        return $this->hasMany(ServiceProduct::class, ['service_id' => 'id']);
    }

    /**
     * Gets query for [[ServiceWorks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getServiceWorks()
    {
        return $this->hasMany(ServiceWork::class, ['service_id' => 'id']);
    }
}
