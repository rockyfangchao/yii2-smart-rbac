<?php

namespace smart\rbac\controllers;

use smart\rbac\exception\UserException;
use smart\rbac\models\Action;
use Yii;
use smart\rbac\models\Auth;
use smart\rbac\models\AuthSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuthController implements the CRUD actions for Auth model.
 */
class AuthController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Auth models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Auth model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*  public function actionView($id)
      {
          return $this->render('view', [
              'model' => $this->findModel($id),
          ]);
      }*/

    /**
     * Creates a new Auth model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        try {

            $model = new Auth();

            if (!Yii::$app->request->isPost) {
                throw new UserException();
            }

            $data = Yii::$app->request->post();
            $data['Auth']['create_at'] = time();
            $data['Auth']['update_at'] = time();
            $ids = $data['Auth']['action_ids'];
            $data['Auth']['action_ids'] = implode(',', $ids);

            if (!$model->load($data) or !$model->save()) {
                throw new UserException("保存失败");
            }

            Yii::$app->session->setFlash('success', '保存成功');
            return $this->refresh();

        } catch (\Exception $e) {

            if (Yii::$app->request->isPost) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }

           /* $actions = $this->getActions();
            echo "<Pre>";print_r($actions);exit;*/
            return $this->render('create', [
                'model' => $model,
                'actions' => $this->getActions()
            ]);

        }
    }

    private function getActions()
    {
        $actions = Action::find()->asArray()->all();
        $actions = ArrayHelper::index($actions, null, 'module_title');
        if ($actions)
            foreach ($actions as $module_name => &$ctrls) {
                $ctrls = ArrayHelper::index($ctrls, null, 'ctrl_title');
            }


        return $actions;
    }

    /**
     * Updates an existing Auth model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {



        try {

            $model = $this->findModel($id);

            if (!Yii::$app->request->isPost) {
                throw new UserException();
            }

            $data = Yii::$app->request->post();
            $data['Auth']['create_at'] = time();
            $data['Auth']['update_at'] = time();
            $ids = $data['Auth']['action_ids'];
            $data['Auth']['action_ids'] = implode(',', $ids);

            if (!$model->load($data) or !$model->save()) {
                throw new UserException("保存失败");
            }

            Yii::$app->session->setFlash('success', '保存成功');
            return $this->refresh();

        } catch (\Exception $e) {

            if (Yii::$app->request->isPost) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }

            /* $actions = $this->getActions();
             echo "<Pre>";print_r($actions);exit;*/
            return $this->render('update', [
                'model' => $model,
                'actions' => $this->getActions()
            ]);

        }
    }

    /**
     * Deletes an existing Auth model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Auth model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Auth the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Auth::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
