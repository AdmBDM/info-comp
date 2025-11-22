<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * Инфо-компы / физические ПК
 *
 * @property int $id
 * @property string $name
 * @property string $hostname
 * @property string|null $source_type
 * @property string|null $source_path
 * @property bool $is_active
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Display[] $displays
 */
class Infocomp extends ActiveRecord
{
    public static function tableName()
    {
        return 'infocomp';
    }

    public function rules()
    {
        return [
            [['name', 'hostname'], 'required'],
            [['is_active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['source_path'], 'string'],
            [['name', 'hostname', 'source_type'], 'string', 'max' => 255],
        ];
    }

    public function getDisplays()
    {
        return $this->hasMany(Display::class, ['infocomp_id' => 'id']);
    }
}
