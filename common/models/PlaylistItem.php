<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * Элементы плейлистов
 *
 * @property int $id
 * @property int $playlist_id
 * @property int $page_id
 * @property int|null $duration
 * @property int|null $order_index
 * @property bool $is_active
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Playlist $playlist
 * @property Page $page
 */
class PlaylistItem extends ActiveRecord
{
    public static function tableName()
    {
        return 'playlist_items';
    }

    public function rules()
    {
        return [
            [['playlist_id', 'page_id'], 'required'],
            [['playlist_id', 'page_id', 'duration', 'order_index'], 'integer'],
            [['is_active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function getPlaylist()
    {
        return $this->hasOne(Playlist::class, ['id' => 'playlist_id']);
    }

    public function getPage()
    {
        return $this->hasOne(Page::class, ['id' => 'page_id']);
    }
}
