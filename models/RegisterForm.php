<?php

namespace app\models;

use Symfony\Component\VarDumper\VarDumper;
use Yii;
use yii\base\Model;
use yii\helpers\VarDumper as HelpersVarDumper;

/**
 * ContactForm is the model behind the contact form.
 */
class RegisterForm extends Model
{
    public string $fio = "";
    public string $login = "";
    public string $phone = "";
    public string $email = "";
    public string $password = "";

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['fio', 'login', 'phone', 'email', 'password'], 'required'],
            // ['fio', 'match', 'pattern' => '/[а-яёa-z\s\-]+$/ui',
            // 'message' => 'Фио должно содержать только кирилицу, латиницу, пробелы и дефисы'],
            // ['email','email'],
            // ['phone', 'match', 'pattern' => '/^\+7 \([0-9]{3}\)\-[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/'
            // , 'message' => 'Телефон должен быть в формате +7 (999)-999-99-99'],
            // ['password', 'match', 'pattern' => '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{6,}$/',
            // 'message' => 'Только латиница и цифры, минимум 6 символов, одна цифра строчная и заглавная буква'],
            // ['login', 'unique', 'targetClass' => User::class], 
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'fio' => 'Фамилия Имя Отчество',
            'login' => 'Логин в системе',
            'phone' => 'Номер телефона',
            'email' => 'Электронная почта',
            'password' => 'Пароль',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function register(): object|bool
    {
        if ($this->validate()) {
            $user = new User;
            $user->load($this->attributes,'');
            $user->role_id = Role::getRoleId('user');
            $user->password = Yii::$app->security->generatePasswordHash($this->password);
            $user->auth_key = Yii::$app->security->generateRandomString();
            if(!$user->save())
            {
               HelpersVarDumper::dump($user->errors,10,true); 
            };
        }
        return $user ?? false;
    }
}
