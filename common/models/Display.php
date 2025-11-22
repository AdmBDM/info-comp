<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * Мониторы инфокомпов для мульти-дисплея
 *
 * @property int $id
 * @property int $infocomp_id
 * @property int $display_index
 * @property string $name
 * @property string|null $orientation
 * @property array|null $config_json
 * @property int|null $playlist_id
 * @property bool $is_active
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Infocomp $infocomp
 * @property Playlist $playlist
 */
class Display extends ActiveRecord
{
    public static function tableName()
    {
        return 'displays';
    }

    public function rules()
    {
        return [
            [['infocomp_id', 'display_index', 'name'], 'required'],
            [['infocomp_id', 'display_index', 'playlist_id'], 'integer'],
            [['is_active'], 'boolean'],
            [['config_json'], 'safe'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['orientation'], 'in', 'range' => ['Ландшафт','Портрет']],
        ];
    }

    public function getInfocomp()
    {
        return $this->hasOne(Infocomp::class, ['id' => 'infocomp_id']);
    }

    public function getPlaylist()
    {
        return $this->hasOne(Playlist::class, ['id' => 'playlist_id']);
    }
}
