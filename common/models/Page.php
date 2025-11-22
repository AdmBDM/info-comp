<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * Страницы для отображения
 *
 * @property int $id
 * @property string $title
 * @property string|null $type
 * @property string|null $template
 * @property string|null $config_json
 * @property bool $is_active
 * @property string $created_at
 * @property string $updated_at
 *
 * @property PlaylistItem[] $playlistItems
 */
class Page extends ActiveRecord
{
    public static function tableName()
    {
        return 'pages';
    }

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['is_active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'type', 'template'], 'string', 'max' => 255],
            [['config_json'], 'string'],
        ];
    }

    public function getPlaylistItems()
    {
        return $this->hasMany(PlaylistItem::class, ['page_id' => 'id']);
    }
}
