<?php
namespace backend\controllers;

use Yii;
use common\models\Infocomp;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * InfocompController CRUD for InfoTV devices
 */
class InfocompController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $models = Infocomp::find()->all();
        return $this->render('index', ['models' => $models]);
    }

    /**
     * @return string|Response
     * @throws Exception
     */
    public function actionCreate(): Response|string
    {
        $model = new Infocomp();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        return $this->render('create', ['model' => $model]);
    }

    /**
     * @param $id
     *
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionUpdate($id): Response|string
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        return $this->render('update', ['model' => $model]);
    }

    /**
     * @param $id
     *
     * @return Infocomp|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id): ?Infocomp
    {
        if (($model = Infocomp::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested Infocomp does not exist.');
    }
}
