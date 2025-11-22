<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * API-ключи для инфо-компов
 *
 * @property int $id
 * @property int $infocomp_id
 * @property string $key
 * @property string $created_at
 *
 * @property Infocomp $infocomp
 */
class ApiKey extends ActiveRecord
{
    public static function tableName()
    {
        return 'api_keys';
    }

    public function rules()
    {
        return [
            [['infocomp_id', 'key'], 'required'],
            [['infocomp_id'], 'integer'],
            [['created_at'], 'safe'],
            [['key'], 'string', 'max' => 64],
        ];
    }

    public function getInfocomp()
    {
        return $this->hasOne(Infocomp::class, ['id' => 'infocomp_id']);
    }

    /**
     * Генерирует случайный API-ключ длиной 64 символа
     * @return string
     */
    public static function generateRandomKey(): string
    {
        return bin2hex(random_bytes(32)); // 32 байта = 64 символа hex
    }

    /**
     * Перед сохранением новой записи автоматически генерируем ключ, если не задан
     */
    public function beforeSave($insert)
    {
        if ($insert && empty($this->key)) {
            $this->key = self::generateRandomKey();
        }
        return parent::beforeSave($insert);
    }
}
