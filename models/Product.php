<?php

namespace app\models;
use yii\web\UploadedFile;//для фотографии(1)
use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $sku
 * @property string $name
 * @property string $brand
 * @property string $description
 * @property int $price
 * @property int $quantity
 * @property string $image_url
 * @property string $created_at
 * @property string $updated_at
 * @property string $attributes
 *
 * @property ServiceProduct[] $serviceProducts
 */
class Product extends \yii\db\ActiveRecord
{

    public $imageFile;//для фотографии(1)



    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sku', 'name', 'brand', 'description', 'price', 'quantity', 'attributes'], 'required'],//убрал imageUrl
            [['description', 'attributes'], 'string'],
            //[['price', 'quantity'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['sku', 'name', 'brand', 'image_url'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, webp'], //для фотографии(2)
            [['image_url'], 'string', 'max' => 255],//для фотографии(3)
            [['name'], 'match', 'pattern' => '/^[a-zA-Z0-9а-яА-ЯёЁ\s\-]+$/u', 'message' => 'Допустимы только буквы, цифры, пробелы и дефис'],
            [['price'], 'match', 'pattern' => '/^\d+$/', 'message' => 'Допустимы только цифры'],
            [['quantity'], 'match', 'pattern' => '/^\d+$/', 'message' => 'Допустимы только цифры'],
            [['sku'], 'match', 'pattern' => '/^[a-zA-Z0-9]+$/u', 'message' => 'Артикул может содержать только латиницу и цифры.'],
            [['sku'], 'string', 'max' => 5],
            [['brand'], 'match', 'pattern' => '/^[a-zA-Z0-9а-яА-ЯёЁ\s\-]+$/u', 'message' => 'Бренд может содержать только буквы и цифры.'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id в системе',
            'sku' => 'Артикул',
            'name' => 'Наименование',
            'brand' => 'Бренд',
            'description' => 'Описание',
            'price' => 'Цена за единицу',
            'quantity' => 'Количество на складе',
            'image_url' => 'Фотогорафия',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
            'attributes' => 'Характеристики',
        ];
    }

    /**
     * Gets query for [[ServiceProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getServiceProducts()
    {
        return $this->hasMany(ServiceProduct::class, ['product_id' => 'id']);
    }


  public function upload()
{
    if ($this->validate()) {
        $fileName = uniqid() . '.' . $this->imageFile->extension;
        $path = Yii::getAlias('@webroot/uploads/products/' . $fileName);
        if ($this->imageFile->saveAs($path)) 
        {
            $this->image_url = '/uploads/products/' . $fileName;
            return true;
        }
    }
    return false;
}



}
