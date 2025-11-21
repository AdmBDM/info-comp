<?php

namespace common\modules\api\controllers\v1;

class DefaultController extends BaseApiController
{
    /**
     * @return string[]
     */
    public function actionIndex(): array
    {
        return [
            'status' => 'ok',
            'message' => 'API v1 работает'
        ];
    }
}
