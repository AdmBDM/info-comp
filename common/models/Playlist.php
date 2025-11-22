<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * Плейлисты страниц
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property bool $is_active
 * @property string $created_at
 * @property string $updated_at
 *
 * @property PlaylistItem[] $items
 * @property Display[] $displays
 */
class Playlist extends ActiveRecord
{
    public static function tableName()
    {
        return 'playlists';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function getItems()
    {
        return $this->hasMany(PlaylistItem::class, ['playlist_id' => 'id']);
    }

    public function getDisplays()
    {
        return $this->hasMany(Display::class, ['playlist_id' => 'id']);
    }
}
