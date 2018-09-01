<?php

namespace smart\rbac\controllers;

use smart\rbac\exception\UserException;
use smart\rbac\models\Action;
use Yii;
use smart\rbac\models\Menu;
use smart\rbac\models\MenuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends Controller
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
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Menu model.
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
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Menu();
        $model->status = 2;
        $model->order_by = 255;
        $model->create_at = time();
        $model->update_at = time();


        try {

            if (Yii::$app->getRequest()->isGet) {
                throw new UserException('');
            }

            $data = Yii::$app->request->post('Menu');
            $data['order_by'] = (int)$data['order_by'];
            $data['parent_id'] = (int)$data['parent_id'];
            $data['order_by'] = (int)$data['parent_id'];

            if (!$model->load(['Menu'=>$data],'Menu')) {
                throw new UserException('数据载入失败');
            }


            if(!empty($model->url)){
                $Action = $this->getActionByRoute($model->url);
                if(!$Action){
                    $model->addError('url','没有找到对应的路由');
                    throw new UserException('没有找到对应的路由');
                }
                $model->action_id = $Action->action_id;
            }

            if (!$model->save()) {
                throw new UserException('保存失败');
            }
            Yii::$app->session->setFlash('success', '保存成功');
            return $this->refresh();

            //return $this->redirect(['view', 'id' => $model->menu_id]);

        } catch (\Exception $e) {

            $actionId = Yii::$app->getRequest()->get('action_id');
            $Action = Action::findOne($actionId);

            if ($Action) {
                $model->url = $Action->route;
                $model->action_id = $Action->action_id;
                $model->menu_name = $Action->action_title;
            }

            if (Yii::$app->getRequest()->isPost) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }

            return $this->render('create', [
                'model' => $model,
            ]);

        }


    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '保存成功');
            return $this->refresh();
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Menu model.
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
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    protected function getActionByRoute($url)
    {
        if (empty($url)) {
            return false;
        }

        $url = trim($url, '/');
        $arrParams = parse_url($url);
        $path = str_ireplace(['.php', '.html', '.htm'], '', $arrParams['path']);

        $arrParams = explode('/', $path);

        $condition = [];
        if (count($arrParams) == 3) {
            $moduleId = $arrParams[0];
            $controllerId = $arrParams[1];
            $actionId = isset($arrParams[2]) ? $arrParams[2] : 'index';
            $condition['route'] = Action::generateRoute($moduleId, $controllerId, $actionId);
        } else {
            throw new UserException('路由填写不正确');
        }

        return Action::find()->select('action_id')->where($condition)->one();

        if (!$Action) {
            throw new UserException('没有找到对应的路由');

        }
        return $Action;

    }

}
