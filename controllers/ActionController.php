<?php

namespace smart\rbac\controllers;

use Yii;
use smart\rbac\models\Action;
use smart\rbac\models\ActionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ActionController implements the CRUD actions for Action model.
 */
class ActionController extends Controller
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
     * Lists all Action models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ActionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Creates a new AdminAction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @title 刷新action
     * @return mixed
     */
    public function actionRefresh()
    {

        $app = Yii::$app;

        $actionMap = [];

        foreach ($app->getModules() as $moduleId => $model) {
            try {
                if ($Module = $app->getModule($moduleId, true)) {

                    $namespace = $Module->controllerNamespace;
                    if (!preg_match('/^app.*/', $namespace)) {
                        continue;
                    }

                    $path = $Module->getControllerPath();
                    $controllers = glob($path . '/*Controller.php');

                    if ($controllers)
                        foreach ($controllers as $ctrl) {
                            $ctrlName = basename(trim($ctrl, '.php'));
                            $ctrl = $namespace . '\\' . $ctrlName;

                            //ECHO "<BR/>";

                            $ref = new \ReflectionClass($ctrl);
                            //$docCommentArr = explode("\n", $ref->getDocComment());

                            $ctrlTitle = $this->parseComment($ref->getDocComment());
                            if (preg_match('/未使用/', $ctrlTitle)) {
                                continue;
                            }


                            $methods = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);

                            $routeCtrlName = Action::convertToControllerId($ctrlName);

                            //
                            if ($methods) {
                                foreach ($methods as $method) {
                                    if (!preg_match("/^action/", $method->name)
                                        or $method->name === 'actionClientValidate'
                                        or $method->name === 'actions'
                                    ) {
                                        //echo $method->name."<br/>";
                                        continue;
                                    }

                                    $actionTitle = $this->parseComment($method->getDocComment());
                                    if (preg_match('/未使用/', $actionTitle)) {
                                        continue;
                                    }

                                    $routeMethodName = Action::convertToActionId($method->name);

                                    $route = $moduleId.'/'.$routeCtrlName.'/'.$routeMethodName;
                                    $model = Action::findOne([
                                        'route'=>$moduleId.'/'.$routeCtrlName.'/'.$routeMethodName
                                    ]);

                                    if (!$model) {
                                        $model = new Action();
                                        $model->create_at = time();
                                        $model->route = $route;
                                    }

                                    //$model->action_id;
                                    $model->module_title = $moduleId;
                                    $model->action_title = empty($actionTitle) ? $routeMethodName : $actionTitle;
                                    $model->ctrl_title = empty($ctrlTitle) ? $routeCtrlName : $ctrlTitle;

                                    if (!empty($model->getDirtyAttributes()) and $model->save(false)) {
                                        $model->update_at = time();
                                        $model->save(false);
                                    } else {
                                        //print_r($model->getErrors());
                                    }

                                    //$kk = $model->module_name.'_'.$model->ctrl_name.'_'.$model->action_name;
                                    $actionMap[$model->action_id] = $model;
                                }
                            }
                            //print_r($actionMap);

                        }

                }

            } catch (\Exception $e) {
                echo $e->getMessage()."<br/>";
                die();
            }

        }

        $dbActionList = Action::find()->indexBy('action_id')->all();
        $delList = array_diff_key($dbActionList, $actionMap);//差集  被删除的action
        foreach ($delList as $model) {
            $model->delete();
        }

        return $this->redirect(['index']);
    }


    private function parseComment($str)
    {

        $arr = explode('\n', $str);

        foreach ($arr as $comment) {
            $pos = stripos($comment, '@title');
            if ($pos > 0) {
                $str = substr($comment, $pos + 6);
                $endPos = stripos($str, '*');
                if ($endPos <= 0) {
                    $endPos = stripos($str, ' ');
                }
                $str = substr($str, 0, ($endPos - 1));
                return trim($str);
            }

        }
    }

}
