<?php

namespace smart\rbac\controllers;

use smart\rbac\exception\UserException;
use smart\rbac\models\Auth;
use Yii;
use smart\rbac\models\Role;
use smart\rbac\models\RoleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RoleController implements the CRUD actions for Role model.
 */
class RoleController extends Controller
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
     * Lists all Role models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Role model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Role model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        try{
            $model = new Role();

            if(Yii::$app->request->isGet){
                throw new UserException();
            }
            $model->create_at = time();
            $model->update_at = time();

            $post = Yii::$app->request->post();
            $post['Role']['auth_ids'] = @implode(',', $post['Role']['auth_ids']);

            if ($model->load($post) && $model->save()) {

            }

            Yii::$app->session->setFlash('success', '保存成功');
            return $this->refresh();

        }
        catch(\Exception $e)
        {
            return $this->render('create', [
                'model' => $model,
            ]);
        }


    }

    /**
     * Updates an existing Role model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        try{
            if(Yii::$app->request->isGet){
                throw new UserException();
            }

            $post = Yii::$app->request->post();
            $post['Role']['auth_ids'] = @implode(',', $post['Role']['auth_ids']);

            if ($model->load($post) && !empty($model->getDirtyAttributes()) && $model->save()  ) {
                $model->update_at = time();
                $model->save();
            }

            Yii::$app->session->setFlash('success', '保存成功');
            return $this->refresh();
        }
        catch(\Exception $e)
        {
            return $this->render('update', [
                'model' => $model,
            ]);
        }



    }

    /**
     * Deletes an existing Role model.
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
     * Finds the Role model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Role the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Role::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
