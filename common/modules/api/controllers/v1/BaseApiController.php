<?php
namespace common\modules\api\controllers\v1;

use yii\rest\Controller;
use yii\web\Response;

/**
 * Базовый класс API-контроллера
 */
class BaseApiController extends Controller
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        $b = parent::behaviors();
        $b['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $b;
    }
}
