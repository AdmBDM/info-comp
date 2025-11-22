<?php
namespace common\modules\api\controllers\v1;

use common\modules\api\controllers\v1\BaseApiController;
use yii\web\Response;

/**
 * Class ManifestController
 * Отдаёт manifest клиенту InfoTV
 */
class ManifestController extends BaseApiController
{
    /**
     * @param $device_uuid
     *
     * @return array
     */
    public function actionIndex($device_uuid): array
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        // Заглушка: возвращаем минимальный набор страниц
        return [
            'device_uuid' => $device_uuid,
            'displays' => [
                [
                    'id' => 1,
                    'playlist' => [
                        ['page' => 'Главная', 'duration' => 10, 'template' => 'main_template'],
                        ['page' => 'Новости', 'duration' => 20, 'template' => 'news_template'],
                    ]
                ]
            ]
        ];
    }
}
