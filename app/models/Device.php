<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "devices".
 *
 * @property int $id
 * @property string $name
 * @property string $ip_address
 * @property string $username
 * @property string $password
 */
class Device extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'device';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'ip_address', 'sshport', 'username', 'password'], 'required'],
            [['name', 'ip_address', 'sshport', 'username'], 'trim'],
            [['sshport'], 'number', 'max' => 65535],
            [['name', 'username', 'password'], 'string', 'max' => 100],
            [['ip_address'], 'ip', 'ipv6' => false, 'subnet' => false],
            [['active'], 'number', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'ip_address' => 'IP адрес',
            'sshport' => 'SSH порт',
            'username' => 'Имя пользователя',
            'password' => 'Пароль',
            'active' => 'Активно?',
            'laststatus' => 'Последний статус',
        ];
    }
}
