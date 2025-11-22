<?php
namespace backend\controllers;

use Yii;
use common\models\Display as Displays;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * DisplaysController CRUD for InfoTV monitors
 */
class DisplaysController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $models = Displays::find()->all();
        return $this->render('index', ['models' => $models]);
    }

    /**
     * @return string|Response
     * @throws Exception
     */
    public function actionCreate(): Response|string
    {
        $model = new Displays();
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
     * @return Displays|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id): ?Displays
    {
        if (($model = Displays::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested Display does not exist.');
    }
}
