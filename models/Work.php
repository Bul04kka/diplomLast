<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "work".
 *
 * @property int $id
 * @property string $name
 * @property int $price
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ServiceWork[] $serviceWorks
 */
class Work extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'work';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'price','description'], 'required'],
            //[['price'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'match', 'pattern' => '/^[a-zA-Z0-9а-яА-ЯёЁ\s\-]+$/u', 'message' => 'Допустимы только буквы, цифры, пробелы и дефис'],
            [['price'], 'match', 'pattern' => '/^\d+$/', 'message' => 'Допустимы только цифры'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Номер в системе',
            'description' => 'Описание',
            'name' => 'Наименование',
            'price' => 'Цена за работу',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
        ];
    }

    /**
     * Gets query for [[ServiceWorks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getServiceWorks()
    {
        return $this->hasMany(ServiceWork::class, ['work_id' => 'id']);
    }
}
