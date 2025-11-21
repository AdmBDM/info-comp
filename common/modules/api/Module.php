<?php
namespace common\modules\api;

use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    public $controllerNamespace = 'common\modules\api\controllers';

    /**
     * @return void
     */
    public function init(): void
    {
        parent::init();
    }

}
